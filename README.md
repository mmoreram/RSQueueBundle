RSQueueBundle for Symfony
=====
### Simple queuing system based on Redis

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/78931ad8-b016-4b5b-9b45-c5a5767fbd9e/mini.png)](https://insight.sensiolabs.com/projects/78931ad8-b016-4b5b-9b45-c5a5767fbd9e)
[![Build Status](https://secure.travis-ci.org/mmoreram/RSQueueBundle.png?branch=master)](http://travis-ci.org/mmoreram/rsqueue-bundle)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/mmoreram/RSQueueBundle/badges/quality-score.png?s=290f904ff14fb72d9d40288682949b3de88f99f9)](https://scrutinizer-ci.com/g/mmoreram/RSQueueBundle/)

Table of contents
-----
1. [Installing/Configuring](#installingconfiguring)
    * [Tags](#tags)
    * [Installing Redis](#installing-redis)
    * [Installing PHPRedis](#installing-phpredis)
    * [Installing RSQueue](#installing-rsqueue)
    * [Configuration](#configuration)
2. [Producers/Consumers](#producersconsumers)
3. [Publishers/Subscribers](#publisherssubscribers)
4. [Events](#events)
5. [Contributing](#contributing)

Installing/Configuring
-----

## Tags

* Use version `1.0-dev` for last updated. Alias of `dev-master`.
* Use last stable version tag to stay in a stable release.

## Installing [Redis](http://redis.io)

``` bash
wget http://download.redis.io/redis-stable.tar.gz
tar xvzf redis-stable.tar.gz
cd redis-stable
make
```

## Installing [PHPRedis](https://github.com/nicolasff/phpredis)

phpredis extension is necessary to be installed in your server.  
Otherwise composer will alert you.

``` bash
git clone git://github.com/nicolasff/phpredis.git
cd phpredis
phpize
./configure
make
sudo make install
cd ..
echo "extension=redis.so" >> `php --ini | grep "Loaded Configuration" | sed -e "s|.*:\s*||"`
```

## Installing [RSQueue](http://rsqueue.com)

You have to add require line into you composer.json file

``` yml
"require": {
    "php": ">=5.3.3",
    "symfony/symfony": "2.3.*",
    ...
    "mmoreram/rsqueue-bundle": "dev-master"
},
```

Then you have to use composer to update your project dependencies

``` bash
php composer.phar update
```

And register the bundle in your appkernel.php file

``` php
return array(
    // ...
    new Mmoreram\RSQueueBundle\RSQueueBundle(),
    // ...
);
```

## Configuration

In this first version, all conections are localhost:6379, but as soon as posible connections will be configurable.  
You need to configure all queues and serializer.  
By default serializer has the value 'Json', but also 'PHP' value can be used. Also custom serializer can be implemented by extending default serializer interface. Then you need to add namespace of class into the rs_queue.serializer parameter.

``` yml
rs_queue:

    # Queues definition
    queues:
        videos: "queues:videos"
        audios: "queues:audios"

    # Serializer definition
    serializer: ~

    # Server configuration. By default, these values
    server:
        redis:
            host: 127.0.0.1
            port: 6379
            database: ~
```

Producers/Consumers
-----
Producer/consumer model allows you to produce elements into one/many queues by using default rsqueue producer service.  
One element is pushed into one queue so one and only one consumer will pop and treat this element.

``` php
$this->container->get("rs_queue.producer")->produce("videos", "this is my video");
$this->container->get("rs_queue.producer")->produce("audios", "this is my audio");
```

Then you should extend ConsumerCommand so that in this way you can define which queues listen, and in each case, which action execute.

``` php
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Mmoreram\RSQueueBundle\Command\ConsumerCommand;

/**
 * Testing consumer command
 */
class TestConsumerCommand extends ConsumerCommand
{

    /**
     * Configuration method
     */
    protected function configure()
    {
        $this
            ->setName('test:consumer')
            ->setDescription('Testing consumer command');
        ;

        parent::configure();
    }

    /**
     * Relates queue name with appropiated method
     */
    public function define()
    {
        $this->addQueue('videos', 'consumeVideo');
    }

    /**
     * If many queues are defined, as Redis respects order of queues, you can shuffle them
     * just overwritting method shuffleQueues() and returning true
     *
     * @return boolean Shuffle before passing to Gearman
     */
    public function shuffleQueues()
    {
        return true;
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
```

Publishers/Subscribers
-----
This model allows data broadcasting. This means that one or more Subscribers will treat all elements of the queue, but only if they are listening just in the moment publisher publish them.
    
``` php
$this->container->get("rs_queue.publisher")->publish("audios", "this is my audio");
```

And, as consumers, subscribers must define which channels they want to listen

``` php
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Mmoreram\RSQueueBundle\Command\SubscriberCommand;

/**
 * Testing subscriber command
 */
class TestSubscriberCommand extends SubscriberCommand
{

    /**
     * Configuration method
     */
    protected function configure()
    {
        $this
            ->setName('test:subscriber:audios')
            ->setDescription('Testing subscriber audios command');
        ;

        parent::configure();
    }

    /**
     * Relates queue name with appropiated method
     */
    public function define()
    {
        $this->addChannel('audios', 'consumeAudio');
    }

    /**
     * If many queues are defined, as Redis respects order of queues, you can shuffle them
     * just overwritting method shuffleQueues() and returning true
     *
     * @return boolean Shuffle before passing to Gearman
     */
    public function shuffleQueues()
    {
        return true;
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
```

By extending PSubscriberCommand you can define patterns instead of queue names.

``` php
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Mmoreram\RSQueueBundle\Command\PSubscriberCommand;

/**
 * Testing PSubscriber command
 */
class TestPSubscriberCommand extends PSubscriberCommand
{

    /**
     * Configuration method
     */
    protected function configure()
    {
        $this
            ->setName('test:psubscriber')
            ->setDescription('Testing psubscriber command');
        ;

        parent::configure();
    }

    /**
     * Relates queue name with appropiated method
     */
    public function define()
    {
        $this->addPattern('*', 'consumeAll');
    }

    /**
     * If many queues are defined, as Redis respects order of queues, you can shuffle them
     * just overwritting method shuffleQueues() and returning true
     *
     * @return boolean Shuffle before passing to Gearman
     */
    public function shuffleQueues()
    {
        return true;
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
```

Events
-----
Custom events are used in this bundle.

``` php
/**
 * The rs_queue.consumer is thrown each time a job is consumed by consumer
 *
 * The event listener recieves an
 * Mmoreram\RSQueueBundle\Event\RSQueueConsumerEvent instance
 *
 * @var string
 */
const RSQUEUE_CONSUMER = 'rs_queue.consumer';

/**
 * The rs_queue.subscriber is thrown each time a job is consumed by subscriber
 *
 * The event listener recieves an
 * Mmoreram\RSQueueBundle\Event\RSQueueSubscriberEvent instance
 *
 * @var string
 */
const RSQUEUE_SUBSCRIBER = 'rs_queue.subscriber';

/**
 * The rs_queue.producer is thrown each time a job is consumed by producer
 *
 * The event listener recieves an
 * Mmoreram\RSQueueBundle\Event\RSQueueProducerEvent instance
 *
 * @var string
 */
const RSQUEUE_PRODUCER = 'rs_queue.producer';

/**
 * The rs_queue.publisher is thrown each time a job is consumed by publisher
 *
 * The event listener recieves an
 * Mmoreram\RSQueueBundle\Event\RSQueuePublisherEvent instance
 *
 * @var string
 */
const RSQUEUE_PUBLISHER = 'rs_queue.publisher';
```

Contributing
-----

All code is Symfony2 Code formatted, so every pull request must validate phpcs
standards. You should read 
[Symfony2 coding standards](http://symfony.com/doc/current/contributing/code/standards.html)
and install [this](https://github.com/opensky/Symfony2-coding-standard) 
CodeSniffer to check all code is validated.

There is also a policy for contributing to this project. All pull request must
be all explained step by step, to make us more understandable and easier to
merge pull request. All new features must be tested with PHPUnit.

If you'd like to contribute, please read the [Contributing Code][1] part of the
documentation. If you're submitting a pull request, please follow the guidelines
in the [Submitting a Patch][2] section and use the [Pull Request Template][3].

[1]: http://symfony.com/doc/current/contributing/code/index.html
[2]: http://symfony.com/doc/current/contributing/code/patches.html#check-list
[3]: http://symfony.com/doc/current/contributing/code/patches.html#make-a-pull-request
