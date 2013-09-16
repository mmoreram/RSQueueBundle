![Payment Suite](http://origin.shields.io/Still/maintained.png?color=green)  [![Build Status](https://secure.travis-ci.org/mmoreram/rsqueue-bundle.png?branch=master)](http://travis-ci.org/mmoreram/rsqueue-bundle)  [![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/mmoreram/RSQueueBundle/badges/quality-score.png?s=290f904ff14fb72d9d40288682949b3de88f99f9)](https://scrutinizer-ci.com/g/mmoreram/RSQueueBundle/)

#RSQueueBundle for Symfony
##Simple queuing system based on Redis

<iframe src="http://www.slideshare.net/MarcMorera/slideshelf" width="490px" height="470px" frameborder="0" marginwidth="0" marginheight="0" scrolling="no" style="border:none;" allowfullscreen webkitallowfullscreen mozallowfullscreen></iframe>

###Installing [Redis](http://redis.io)
    wget http://download.redis.io/redis-stable.tar.gz
    tar xvzf redis-stable.tar.gz
    cd redis-stable
    make

###Installing [PHPRedis](https://github.com/nicolasff/phpredis)
phpredis extension is necessary to be installed in your server.  
Otherwise composer will alert you.

    git clone git://github.com/nicolasff/phpredis.git
    cd phpredis
    phpize
    ./configure
    make
    sudo make install
    cd ..
    echo "extension=redis.so" >> `php --ini | grep "Loaded Configuration" | sed -e "s|.*:\s*||"`

###Installing [RSQueue](http://rsqueue.com)
You have to add require line into you composer.json file

    "require": {
        "php": ">=5.3.3",
        "symfony/symfony": "2.3.*",
        ...
        "mmoreram/rsqueue-bundle": "dev-master"
    },

Then you have to use composer to update your project dependencies

    php composer.phar update

And register the bundle in your appkernel.php file

    return array(
        // ...
        new Mmoreram\RSQueueBundle\RSQueueBundle(),
        // ...
    );

###Configuring RSQueue
In this first version, all conections are localhost:6379, but as soon as posible connections will be configurable.  
You need to configure all queues and serializer.  
By default serializer has the value 'Json', but also 'PHP' value can be used. Also custom serializer can be implemented by extending default serializer interface. Then you need to add namespace of class into the rs_queue.serializer parameter.


    rs_queue:
        queues:
            videos: "queues:videos"
            audios: "queues:audios"
        serializer: ~

###Producers/Consumers
Producer/consumer model allows you to produce elements into one/many queues by using default rsqueue producer service.  
One element is pushed into one queue so one and only one consumer will pop and treat this element.

    $this->container->get("rsqueue.producer")->produce("videos", "this is my video");
    $this->container->get("rsqueue.producer")->produce("audios", "this is my audio");

Then you should extend ConsumerCommand so that in this way you can define which queues listen, and in each case, which action execute.

    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Mmoreram\RSQueueBundle\Command\ConsumerCommand;

    /**
     * Testing consumer command
     */
    class TestConsumerCommand extends ConsumerCommand
    {

        protected function configure()
        {
            $this
                ->setName('test:consumer')
                ->setDescription('Testing consumer command');
            ;

            parent::configure();
        }

        public function define()
        {
            $this->addQueue('videos', 'consumeVideo');
        }

        /**
         * Consume method with retrieved queue value
         *
         * @param InputInterface  $input   An InputInterface instance
         * @param OutputInterface $output  An OutputInterface instance
         * @param Mixed           $payload Data retrieved and unserialized from queue
         */
        protected function consumeVideo(InputInterface $input, OutputInterface $output, $payload)
        {
            $output->writeln($payload);
        }
    }

###Publishers/Subscribers
This model allows data broadcasting. This means that one or more Subscribers will treat all elements of the queue, but only if they are listening just in the moment publisher publish them.
    
    $this->container->get("rsqueue.publisher")->publish("audios", "this is my audio");

And, as consumers, subscribers must define which channels they want to listen

    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Mmoreram\RSQueueBundle\Command\SubscriberCommand;

    /**
     * Testing subscriber command
     */
    class TestSubscriberCommand extends SubscriberCommand
    {

        protected function configure()
        {
            $this
                ->setName('test:subscriber:audios')
                ->setDescription('Testing subscriber audios command');
            ;

            parent::configure();
        }

        public function define()
        {
            $this->addChannel('audios', 'consumeAudio');
        }

        /**
         * subscriber method with retrieved queue value
         *
         * @param InputInterface  $input   An InputInterface instance
         * @param OutputInterface $output  An OutputInterface instance
         * @param Mixed           $payload Data retrieved and unserialized from queue
         */
        protected function consumeAudio(InputInterface $input, OutputInterface $output, $payload)
        {
            $output->writeln($payload);
        }
    }

By extending PSubscriberCommand you can define patterns instead of queue names.

    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Mmoreram\RSQueueBundle\Command\PSubscriberCommand;

    /**
     * Testing PSubscriber command
     */
    class TestPSubscriberCommand extends PSubscriberCommand
    {

        protected function configure()
        {
            $this
                ->setName('test:psubscriber')
                ->setDescription('Testing psubscriber command');
            ;

            parent::configure();
        }

        public function define()
        {
            $this->addPattern('*', 'consumeAll');
        }

        /**
         * Consume method with retrieved queue value
         *
         * @param InputInterface  $input   An InputInterface instance
         * @param OutputInterface $output  An OutputInterface instance
         * @param Mixed           $payload Data retrieved and unserialized from queue
         */
        protected function consumeAll(InputInterface $input, OutputInterface $output, $payload)
        {
            $output->writeln($payload);
        }
    }

###Events
Custom events are used in this bundle.

    /**
     * The rsqueue.consumer is thrown each time a job is consumed by consumer
     *
     * The event listener recieves an
     * Mmoreram\RSQueueBundle\Event\RSQueueConsumerEvent instance
     *
     * @var string
     */
    const RSQUEUE_CONSUMER = 'rsqueue.consumer';

    /**
     * The rsqueue.subscriber is thrown each time a job is consumed by subscriber
     *
     * The event listener recieves an
     * Mmoreram\RSQueueBundle\Event\RSQueueSubscriberEvent instance
     *
     * @var string
     */
    const RSQUEUE_SUBSCRIBER = 'rsqueue.subscriber';

    /**
     * The rsqueue.producer is thrown each time a job is consumed by producer
     *
     * The event listener recieves an
     * Mmoreram\RSQueueBundle\Event\RSQueueProducerEvent instance
     *
     * @var string
     */
    const RSQUEUE_PRODUCER = 'rsqueue.producer';

    /**
     * The rsqueue.publisher is thrown each time a job is consumed by publisher
     *
     * The event listener recieves an
     * Mmoreram\RSQueueBundle\Event\RSQueuePublisherEvent instance
     *
     * @var string
     */
    const RSQUEUE_PUBLISHER = 'rsqueue.publisher';

###In development
* Connection managment
* Monitoring features
* Documentation with some interesting data about queues

### What else?
This bundle is currently being tested.  
Every comment, or issue, or help will be thankful.
