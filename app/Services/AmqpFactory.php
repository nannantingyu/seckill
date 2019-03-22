<?php
namespace App\Services;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;
use App\Repository\Dao\OrderRepository;

class AmqpFactory
{
    public static $connection = null;
    const DIRECT_EXCHANGE = 'OnePiece';
    const DIRECT_QUEUE = 'OnePiece';

    public static function getConnection() {
        if (is_null(self::$connection)) {
            self::$connection = new AMQPStreamConnection(
                config('site.amqp_host'),
                config('site.amqp_port'),
                config('site.amqp_user'),
                config('site.amqp_password')
            );
        }

        return self::$connection;
    }

    public static function publishMessageDirect($message, $queue, $exchange) {
        $connection = self::getConnection();
        $channel = $connection->channel();

        $channel->queue_declare($queue, false, true, false, false);
        $channel->exchange_declare($exchange, AMQPExchangeType::DIRECT);

        $channel->exchange_bind($exchange, $queue);

        $msg = new AMQPMessage($message, [
            'content-type'  =>  'text/plain',
            'delivery_mode' =>  AMQPMessage::DELIVERY_MODE_PERSISTENT
        ]);

        $channel->basic_publish($msg, $exchange);
        $channel->close();
    }

    /**
     * @param $exchange
     * @param $queue
     * @param $callback
     * @param string $consumerTag
     * @throws \ErrorException
     */
    public static function consumeDirect($exchange, $queue, $callback, $consumerTag='consumer') {
        $connection = self::getConnection();
        $channel = $connection->channel();

        $channel->queue_declare($queue, false, true, false, false);
        $channel->exchange_declare($exchange, AMQPExchangeType::DIRECT);

        $channel->queue_bind($queue, $exchange);
        $channel->basic_consume($queue, $consumerTag, false, false, false, false, $callback);

        while (count($channel->callbacks)) {
            $channel->wait();
        }
    }

    public function __destruct()
    {
        if (self::$connection) {
            self::$connection->close();
        }
    }
}