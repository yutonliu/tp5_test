<?php
namespace app\index\controller;
use think\Controller;

class Index  extends Controller
{
    public function index()
    {
    	  return $this->fetch('index');
    }

	public function outxls() {
		//从数据库查询出的数据
		$list = [
			[
				'id'=>1,
				'type'=>'大客户',
				'kehuname'=>'客户',
				'linkman'=>'孙小姐',
				'tel'=>'137510',
				'remark'=>'hhhhhhh'
			],
			[
				'id'=>2,
				'type'=>'大客户',
				'kehuname'=>'客户',
				'linkman'=>'王先生',
				'tel'=>'182654321',
				'remark'=>'6666'
			],
			[
				'id'=>2,
				'type'=>'大客户',
				'kehuname'=>'客户',
				'linkman'=>'王先生',
				'tel'=>'182654321',
				'remark'=>'6666'
			],
			[
				'id'=>2,
				'type'=>'大客户',
				'kehuname'=>'客户',
				'linkman'=>'王先生',
				'tel'=>'182654321',
				'remark'=>'6666'
			],
			[
				'id'=>2,
				'type'=>'大客户',
				'kehuname'=>'客户',
				'linkman'=>'王先生',
				'tel'=>'182654321',
				'remark'=>'6666'
			]
		];
		$headArr=array('ID','需求类型','客户名称','联系人','手机号码','备注');
		$filename='客户管理';
		$this->xlsout($filename,$headArr,$list);
	}

    public function xlsout($filename='数据表',$headArr,$list)
	{
		//导入PHPExcel类库，因为PHPExcel没有用命名空间，只能import导入
		import("PHPExcel",EXTEND_PATH);
		import("PHPExcel.Writer.Excel5",EXTEND_PATH);
		import("PHPExcel.IOFactory.php",EXTEND_PATH);
		$this->getExcel($filename,$headArr,$list);
	}

	public	function getExcel($fileName,$headArr,$data){
		//对数据进行检验
		if(empty($data) || !is_array($data)){
			die("data must be a array");
		}
		//检查文件名
		if(empty($fileName)){
			exit;
		}

		$date = date("Y_m_d",time());
		$fileName .= "_{$date}.xls";


		//创建PHPExcel对象，注意，不能少了\
		$objPHPExcel = new \PHPExcel();
		$objProps = $objPHPExcel->getProperties();

		//设置表头
		$key = 0;
		//print_r($headArr);exit;
		foreach($headArr as $v){
			//注意，不能少了。将列数字转换为字母\
			$colum = \PHPExcel_Cell::stringFromColumnIndex($key);
			$objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
			$key += 1;
		}
		$column = 2;
		$objActSheet = $objPHPExcel->getActiveSheet();

		foreach($data as $key => $rows){ //行写入
			$span = 0;
			foreach($rows as $keyName=>$value){// 列写入
				$j = \PHPExcel_Cell::stringFromColumnIndex($span);
				$objActSheet->setCellValue($j.$column, $value);
				$span++;
			}
			$column++;
		}


		$fileName = iconv("utf-8", "gb2312", $fileName);
		//重命名表
		// $objPHPExcel->getActiveSheet()->setTitle('test');
		//设置活动单指数到第一个表,所以Excel打开这是第一个表
		$objPHPExcel->setActiveSheetIndex(0);
		ob_end_clean();//清除缓冲区,避免乱码
		header('Content-Type: application/vnd.ms-excel');
		header("Content-Disposition: attachment;filename=\"$fileName\"");
		header('Cache-Control: max-age=0');

		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output'); //文件通过浏览器下载
		exit;
	}

	public function xlsin(){

		//导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入
		import("Org.Util.PHPExcel");
		//要导入的xls文件，位于根目录下的Public文件夹
		$filename="./Public/1.xls";
		//创建PHPExcel对象，注意，不能少了\
		$PHPExcel=new \PHPExcel();
		//如果excel文件后缀名为.xls，导入这个类
		import("Org.Util.PHPExcel.Reader.Excel5");
		//如果excel文件后缀名为.xlsx，导入这下类
		//import("Org.Util.PHPExcel.Reader.Excel2007");
		//$PHPReader=new \PHPExcel_Reader_Excel2007();

		$PHPReader=new \PHPExcel_Reader_Excel5();
		//载入文件
		$PHPExcel=$PHPReader->load($filename);
		//获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
		$currentSheet=$PHPExcel->getSheet(0);
		//获取总列数
		$allColumn=$currentSheet->getHighestColumn();
		//获取总行数
		$allRow=$currentSheet->getHighestRow();
		//循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始
		for($currentRow=1;$currentRow<=$allRow;$currentRow++){
			//从哪列开始，A表示第一列
			for($currentColumn='A';$currentColumn<=$allColumn;$currentColumn++){
				//数据坐标
				$address=$currentColumn.$currentRow;
				//读取到的数据，保存到数组$arr中
				$arr[$currentRow][$currentColumn]=$currentSheet->getCell($address)->getValue();
			}

		}

	}
}
