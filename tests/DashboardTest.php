<?php

namespace artworx\omegacp\Tests;

use Illuminate\Support\Facades\Auth;

class DashboardTest extends TestCase
{
    protected $withDummy = true;

    public function setUp()
    {
        parent::setUp();

        $this->install();
    }

    public function testWeHaveAccessToTheMainSections()
    {
        // We must first login and visit the dashboard page.
        Auth::loginUsingId(1);

        $this->visit(route('omega.dashboard'));

        $this->see('Dashboard');

        // We can see number of Users.
        $this->see('1 user');

        // list them.
        $this->click('View all users');
        $this->seePageIs(route('omega.users.index'));

        // and return to dashboard from there.
        $this->click('Dashboard');
        $this->seePageIs(route('omega.dashboard'));

        // We can see number of posts.
        $this->see('4 posts');

        // list them.
        $this->click('View all posts');
        $this->seePageIs(route('omega.posts.index'));

        // and return to dashboard from there.
        $this->click('Dashboard');
        $this->seePageIs(route('omega.dashboard'));

        // We can see number of Pages.
        $this->see('1 page');

        // list them.
        $this->click('View all pages');
        $this->seePageIs(route('omega.pages.index'));

        // and return to Dashboard from there.
        $this->click('Dashboard');
        $this->seePageIs(route('omega.dashboard'));
        $this->see('Dashboard');
    }
}
