<?php

namespace Obokaman\Application\Service;

use Obokaman\Domain\Kernel\EventStore;
use Obokaman\Domain\Service\EventDispatcher;

final class EventAwareApplicationServiceDecorator implements ApplicationService
{
    /** @var ApplicationService */
    private $application_service;

    /** @var EventDispatcher */
    private $event_dispatcher;

    public function __construct(
        ApplicationService $an_application_service,
        EventDispatcher $an_event_dispatcher
    )
    {
        $this->application_service = $an_application_service;
        $this->event_dispatcher    = $an_event_dispatcher;
    }

    public function __invoke()
    {
        $this->clearEvents();

        $result = $this->invokeDecoratedApplicationService(func_get_args());

        $events = EventStore::instance()->getEvents();

        $this->dispatchEvents($events);

        return $result;
    }

    private function invokeDecoratedApplicationService($original_arguments)
    {
        $result = call_user_func_array([$this->application_service, '__invoke'], $original_arguments);

        return $result;
    }

    private function dispatchEvents($events)
    {
        foreach ($events as $event)
        {
            $this->event_dispatcher->dispatch($event);
        }
    }

    private function clearEvents()
    {
        EventStore::instance()->clearEvents();
    }
}
