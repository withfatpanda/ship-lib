<?php
namespace FatPanda\WordPress\Ship\Bucket;

use Illuminate\Support\Collection;

/**
 * A simple place to keep track of WordPress posts we've already displayed.
 */
class Bucket {

  private $items;

  private $disabled = false;

  function __construct()
  {
    $this->items = new Collection;
  }

  function disable()
  {
    $this->disabled = true;
  }

  function enable()
  {
    $this->disabled = false;
  }

  function put($post, $group = 'default')
  {
    if (!is_object($post = $this->normalize($post))) {
      return false;
    }

    // standard wrapper
    $wrapped = [
      'id' => $post->ID,
      'group' => $group,
      'post' => $post,
    ];

    $this->items->push($wrapped);

    return $wrapped;
  }

  function normalize($post)
  {
    // normalize input
    if (!is_object($post) && !is_array($post)) {
      $post = get_post($post);

    } else if (is_array($post)) {
      if (!empty($post['ID'])) {
        $post = get_post($post['ID']);
      } else if (!empty($post['id'])) {
        $post = get_post($post['id']);
      } else if (!empty($post['post_id'])) {
        $post = get_post($post['post_id']);
      } else if (!empty($post['post_ID'])) {
        $post = get_post($post['post_ID']);
      }
    }

    return $post;
  }

  function all()
  {
    return $this->items;
  }

  function get($group = 'default')
  {
    return $this->items->where('group', $group);
  }

  function filter($posts, $group = 'default')
  {
    return collect($posts)->filter()->map(function($post) {
      return $this->normalize($post);
    })->filter(function($post) {
      return $this->disabled || !$this->items->where('id', $post->ID)->count();
    });
  }

  function count($group = 'default')
  {
    return $this->get($group)->count();
  }

  function dump($group = 'default')
  {
    return $this->items = $this->items->filter(function($in) use ($group) {
      // if the item isn't in the group, keep it
      return $in['group'] !== $group;
    });
  }

}