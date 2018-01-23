<?php
use FatPanda\WordPress\Ship\Bucket\Facade as Bucket;
use Carbon\Carbon;
use Illuminate\Support\Debug\Dumper;

if (!function_exists('now')) {
  /**
   * Create a new Carbon instance for the current time.
   *
   * @param  \DateTimeZone|string|null $tz
   * @return \Illuminate\Support\Carbon
   */
  function now($tz = null)
  {
    if (is_null($tz)) {
      $tz = get_option('timezone_string') ?: null;
    }
    return Carbon::now($tz);
  }
}

if (!function_exists('carbon')) {
  /**
   * Create a new Carbon instance for the given date string,
   * but adjust for local timezone
   *
   * @param  string
   * @return \Illuminate\Support\Carbon
   */
  function carbon($dateTime)
  {
    return now()->parse($dateTime);
  }
}

if (!function_exists('unique')) {
  /**
   * From the given posts, return a Collection that contains
   * only those posts that have not already been used to 
   * render the current request.
   *
   * @param  mixed $posts an array or a Collection
   * @param  String Optionally, partition the enforcement of
   * non-repeating posts into groups; the default group is named "default"
   * @return  mixed Illuminate\Support\Collection or false when there
   * are no non-repeating posts remaining
   */
  function unique($posts = [], $group = 'default')
  {
    $filtered = Bucket::filter($posts, $group);
    return $filtered->count() > 0 ? $filtered : false;
  }
}

if (!function_exists('earmark')) {
  /**
   * Given a list of posts, filter out non-unique and then mark
   * up to the given maximum as spoken-for
   *
   * @param  int $max The number you want, at most, to earmark
   * @param  mixed $posts an array or a Collection
   * @param  String Optionally, partition the enforcement of
   * non-repeating posts into groups; the default group is named "default"
   * @return  mixed Illuminate\Support\Collection or false when there
   * are no non-repeating posts remaining
   */
  function earmark($max, $posts = [], $group = 'default')
  {
    $count = 0;
    $earmarked = Bucket::filter($posts, $group)->each(function($post) use ($max, &$count) {
      return ++$count < (int) $max && do_not_repeat($post);
    });
    return $earmarked->count() > 0 ? $earmarked : false;
  }
}

if (!function_exists('do_not_repeat')) {
  /**
   * Remember this post, and do not repeat it while
   * rendering the current request
   *  
   * @param  mixed $post
   * @param  String Optionally, partition the enforcement of
   * non-repeating posts into groups; the default group is named "default"
   * @see non_repeating()
   */
  function do_not_repeat($post, $group = 'default')
  {
    return Bucket::put($post, $group);
  }
}