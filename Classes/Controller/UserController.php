<?php

namespace AgoraTeam\Agora\Controller;

/***************************************************************
 *  Copyright notice
 *  (c) 2015 Philipp Thiele <philipp.thiele@phth.de>
 *           Björn Christopher Bresser <bjoern.bresser@gmail.com>
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
use AgoraTeam\Agora\Domain\Model\Post;
use AgoraTeam\Agora\Domain\Model\Thread;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Web\Request;

/**
 * UserController
 */
class UserController extends ActionController
{
    /**
     * Action favoritePosts
     *
     * @return void
     */
    public function favoritePostsAction()
    {
        $user = $this->authenticationService->getUser();
        if (is_a($user, '\AgoraTeam\Agora\Domain\Model\User') && $user->getFavoritePosts() !== null) {
            $limit = $this->settings['post']['numberOfItemsInFavoritePostsWidget'];
            $allFavoritedPosts = $user->getFavoritePosts()->toArray();
            $favoritedPosts = array();
            $i = 0;

            /** @var Post $post */
            foreach ($allFavoritedPosts as $post) {
                if ($post->checkAccess($user)) {
                    $favoritedPosts[] = $post;
                    $i++;
                }
                if ($limit == $i) {
                    continue;
                }
            }
        }

        $this->view->assign('user', $user);
        $this->view->assign('favoritePosts', array_reverse($favoritedPosts));
        $this->view->assign('listPid', $this->settings['listView']);
    }

    /**
     * Action observedThreads
     *
     * @return void
     */
    public function observedThreadsAction()
    {
        $user = $this->authenticationService->getUser();
        if (is_a($user, '\AgoraTeam\Agora\Domain\Model\User') && $user->getObservedThreads() !== null) {
            $limit = $this->settings['thread']['numberOfItemsInObservedThreadsWidget'];
            $allObservedThreads = $user->getObservedThreads()->toArray();
            $observedThreads = array();
            $i = 0;

            /** @var Thread $thread */
            foreach ($allObservedThreads as $thread) {
                if ($thread->checkAccess($user)) {
                    $observedThreads[] = $thread;
                    $i++;
                }
                if ($limit == $i) {
                    continue;
                }
            }
        }
        $this->view->assign('user', $user);
        $this->view->assign('observedThreads', $observedThreads);
        $this->view->assign('listPid', $this->settings['listView']);
    }

    /**
     * Action listObservedThreads
     *
     * @return void
     */
    public function listObservedThreadsAction()
    {
        $user = $this->authenticationService->getUser();
        if (is_a($user, '\AgoraTeam\Agora\Domain\Model\User')) {
            $observedThreads = $user->getObservedThreads();
        }
        $this->view->assign('user', $user);
        $this->view->assign('observedThreads', $observedThreads);
    }

    /**
     * Action addObservedThreadAction
     *
     * @param \AgoraTeam\Agora\Domain\Model\Thread $thread
     * @param int $page
     * @return void
     */
    public function addObservedThreadAction(\AgoraTeam\Agora\Domain\Model\Thread $thread, $page = 0)
    {
        $user = $this->authenticationService->getUser();
        if (is_a($user, '\AgoraTeam\Agora\Domain\Model\User')) {
            $user->addObservedThread($thread);
            $this->userRepository->update($user);
        }
        $redirectArgs = ['thread' => $thread];
        if ($page > 1) {
            $redirectArgs['page'] = $page;
        }
        $this->redirect('list', 'Post', 'Agora', $redirectArgs);
    }

    /**
     * Action removeObservedThreadAction
     *
     * @param \AgoraTeam\Agora\Domain\Model\Thread $thread
     * @param int $page
     * @return void
     */
    public function removeObservedThreadAction(\AgoraTeam\Agora\Domain\Model\Thread $thread, $page = 0)
    {
        $user = $this->authenticationService->getUser();
        $user->removeObservedThread($thread);
        $this->userRepository->update($user);
        $redirectArgs = ['thread' => $thread];
        if ($page > 1) {
            $redirectArgs['page'] = $page;
        }
        $this->redirect('list', 'Post', 'Agora', $redirectArgs);
    }

    /**
     * Action addFavoritePostAction
     *
     * @param \AgoraTeam\Agora\Domain\Model\Post $post
     * @return void
     */
    public function addFavoritePostAction(\AgoraTeam\Agora\Domain\Model\Post $post)
    {
        $user = $this->authenticationService->getUser();
        if (is_a($user, '\AgoraTeam\Agora\Domain\Model\User')) {
            $user->addFavoritePost($post);
            $this->userRepository->update($user);
        }
        // Build up the redirect
        $uri = $this->generatePostUri($post, true);
        $this->redirectToUri($uri);
    }

    /**
     * Action removeFavoritePostAction
     *
     * @param \AgoraTeam\Agora\Domain\Model\Post $post
     * @return void
     */
    public function removeFavoritePostAction(\AgoraTeam\Agora\Domain\Model\Post $post)
    {
        $user = $this->authenticationService->getUser();
        if (is_a($user, '\AgoraTeam\Agora\Domain\Model\User')) {
            $user->removeFavoritePost($post);
            $this->userRepository->update($user);
        }
        // Build up the redirect
        $uri = $this->generatePostUri($post, true);
        $this->redirectToUri($uri);
    }

}
