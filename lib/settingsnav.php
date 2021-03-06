<?php
/**
 * StatusNet - the distributed open-source microblogging tool
 * Copyright (C) 2010,2011, StatusNet, Inc.
 *
 * Settings menu
 * 
 * PHP version 5
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category  Widget
 * @package   StatusNet
 * @author    Evan Prodromou <evan@status.net>
 * @copyright 2010,2011 StatusNet, Inc.
 * @license   http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL 3.0
 * @link      http://status.net/
 */

if (!defined('STATUSNET')) {
    // This check helps protect against security problems;
    // your code file can't be executed directly from the web.
    exit(1);
}

/**
 * A widget for showing the settings group local nav menu
 *
 * @category Widget
 * @package  StatusNet
 * @author   Evan Prodromou <evan@status.net>
 * @license  http://www.fsf.org/licensing/licenses/agpl-3.0.html GNU Affero General Public License version 3.0
 * @link     http://status.net/
 *
 * @see      HTMLOutputter
 */

class SettingsNav extends Menu
{
    /**
     * Show the menu
     *
     * @return void
     */

    function show()
    {
        $actionName = $this->action->trimmed('action');
        $user = common_current_user();
        $nickname = $user->nickname;
        $name = $user->getProfile()->getBestName();

        // Stub section w/ home link
        $this->action->elementStart('ul');
        $this->action->element('h3', null, _('Home'));
        $this->action->elementStart('ul', 'nav');
        $this->out->menuItem(common_local_url('all', array('nickname' =>
                                                           $nickname)),
                             _('Home'),
                             sprintf(_('%s and friends'), $name),
                             $this->action == 'all', 'nav_timeline_personal');

        $this->action->elementEnd('ul');
        $this->action->elementEnd('ul');

        $this->action->elementStart('ul');
        $this->action->element('h3', null, _('Settings'));
        $this->action->elementStart('ul', array('class' => 'nav'));

        if (Event::handle('StartAccountSettingsNav', array(&$this->action))) {
            $this->action->menuItem(common_local_url('profilesettings'),
                                    _('Profile'),
                                    _('Change your profile settings'),
                                    $actionName == 'profilesettings');

            $this->action->menuItem(common_local_url('avatarsettings'),
                                    _('Avatar'),
                                    _('Upload an avatar'),
                                    $actionName == 'avatarsettings');

            $this->action->menuItem(common_local_url('passwordsettings'),
                                    _('Password'),
                                    _('Change your password'),
                                    $actionName == 'passwordsettings');

            $this->action->menuItem(common_local_url('emailsettings'),
                                    _('Email'),
                                    _('Change email handling'),
                                    $actionName == 'emailsettings');

            $this->action->menuItem(common_local_url('userdesignsettings'),
                                    _('Design'),
                                    _('Design your profile'),
                                    $actionName == 'userdesignsettings');

            $this->action->menuItem(common_local_url('urlsettings'),
                                    _('URL'),
                                    _('URL shorteners'),
                                    $actionName == 'urlsettings');

            Event::handle('EndAccountSettingsNav', array(&$this->action));
        
            if (common_config('xmpp', 'enabled')) {
                $this->action->menuItem(common_local_url('imsettings'),
                                        _m('IM'),
                                        _('Updates by instant messenger (IM)'),
                                        $actionName == 'imsettings');
            }

            if (common_config('sms', 'enabled')) {
                $this->action->menuItem(common_local_url('smssettings'),
                                        _m('SMS'),
                                        _('Updates by SMS'),
                                        $actionName == 'smssettings');
            }

            $this->action->menuItem(common_local_url('oauthconnectionssettings'),
                                    _('Connections'),
                                    _('Authorized connected applications'),
                                    $actionName == 'oauthconnectionsettings');

            Event::handle('EndConnectSettingsNav', array(&$this->action));
        }

        $this->action->elementEnd('ul');
        $this->action->elementEnd('ul');
    }
}
