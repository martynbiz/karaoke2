<?php
namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Utils;
use App\Model\Song;

class TagsController extends BaseController
{
    /**
     * Will populate the db with file name data
     */
    public function edit($request, $response, $args)
    {
        $container = $this->getContainer();
        $tags = $container->get('model.tag')->orderBy('name')->get();

        return $this->render('admin/tags/edit', [
            'tags' => $tags,
        ]);
    }
    /**
     * Will populate the db with file name data
     */
    public function update($request, $response, $args)
    {
        $container = $this->getContainer();

        // get ids from form (set these to valid)
        $validTagIds = $request->getParam('tag_ids');
        $tags = $container->get('model.tag')->get();
        foreach ($tags as $tag) {
            $tag->update([
                'is_valid' => in_array($tag->id, $validTagIds),
            ]);
        }

        $container->get('flash')->addMessage('success', sprintf($container->get('i18n')->translate('tags_been_updated'), $song->name));

        return $response->withRedirect( $container->get('router')->pathFor('admin_tags_edit') );
    }
}
