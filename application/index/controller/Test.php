<?php
namespace app\index\controller;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
class Test
{
    public function producer()
    {
      //echo 12345;	
      $connection = new AMQPStreamConnection('192.168.13.157', 5672, 'guest', 'guest');
      //print_r($connection);
      $channel = $connection->channel();

      $channel->queue_declare('hello', false, false, false, false);
      $msg = new AMQPMessage('Nice to meet u!');
      $channel->basic_publish($msg, '', 'hello');

      echo " [x] Sent 'Hello World!'\n";
      $channel->close();
      $connection->close();
    }
   
    public function customer()
    {
      $connection = new AMQPStreamConnection('192.168.13.157', 5672, 'guest', 'guest');
      $channel = $connection->channel();

      $channel->queue_declare('hello', false, false, false, false);

      echo " [*] Waiting for messages. To exit press CTRL+C\n";
      
      $callback = function ($msg) {
  	echo ' [x] Received ', $msg->body, "\n";
      };

      $channel->basic_consume('hello', '', false, true, false, false, $callback);

      while (count($channel->callbacks)) {
        $channel->wait();
      }      
    }	
    
}
