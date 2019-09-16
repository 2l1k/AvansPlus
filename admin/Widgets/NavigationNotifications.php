<?php

namespace Admin\Widgets;

use AdminTemplate;
use App\Model\LoanHistoryEvent;
use SleepingOwl\Admin\Navigation\Page;
use SleepingOwl\Admin\Widgets\Widget;

class NavigationNotifications extends Widget
{

    /**
     * Get content as a string of HTML.
     *
     * @return string
     */
    public function toHtml()
    {
        $totalSystemEvents = LoanHistoryEvent::withSystem()->orderBy("id", "desc")->withUnread()->count();
        $system_events = LoanHistoryEvent::withSystem()->orderBy("id", "desc")->withUnread()->take(10)->get();
        $system_events_action = (new Page(\App\Model\LoanHistoryEvent::class))->getUrl() . "?history_key=system&is_read=0";
        return view('admin::navigation.notifications', [
            'user' => auth()->user(),
            'system_events' => $system_events,
            'totalSystemEvents' => $totalSystemEvents,
            'system_events_action' => $system_events_action,
        ])->render();
    }

    /**
     * @return string|array
     */
    public function template()
    {
        return AdminTemplate::getViewPath('_partials.header');
    }

    /**
     * @return string
     */
    public function block()
    {
        return 'navbar.right';
    }
}