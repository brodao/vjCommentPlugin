<?php

/**
 * comment tools.
 *
 * @package    vjCommentPlugin
 * @subpackage tools
 * @author     Jean-Philippe MORVAN <jp.morvan@ville-villejuif.fr>
 * @version    4 mars 2010 10:45:36
 */
class commentTools
{
  protected static $patterns = array(
      'QUOTE_START'   => '<blockquote>',
      'QUOTE_END'     => '</blockquote>',
      'DIV_START'     => '<div>',
      'DIV_END'       => '</div>',
      'STRONG_START'  => '<strong>',
      'STRONG_END'    => '</strong>',
      'BREAK'         => '<br />'
  );

  /**
   * Transform datetime to mktime
   *
   * @param string $datetime A Mysql datetime
   * @return integer A mktime
   */
  public static function getMktime($datetime)
  {
    list($date, $time) = explode(" ", $datetime);
    list($y, $m, $d) = explode("-", $date);
    list($h, $mi, $s) = explode(":", $time);
    return mktime($h, $mi, $s, $m, $d, $y);
  }

  /**
   * Verify that sfGravatarPlugin is installed and gravatar is activated
   *
   * @return boolean
   *
   */
  public static function isGravatarAvailable()
  {
    return sfConfig::get('app_gravatar_enabled');
  }

  /**
   * Add blockquote to the message
   *
   * @param string $author Author name
   * @param string $body Message
   * @return string Message with the right blockquote message
   */
  public static function setQuote($author, $body)
  {
    return <<<EOF
<blockquote>
  <div>
    <strong>$author</strong>
  </div>
  $body
</blockquote>
<br />
EOF;
  }

  /**
   * Remove HTML tags, blockquote part and cut down
   *
   * @param string $content Message
   * @return string Message without blockquote and cut down
   */
  public static function cleanQuote($content = "", $cut = false)
  {
    if(preg_match("/<br \/>/", $content))
    {
      $content = substr(strip_tags(strrchr($content, '<br />')), 1);
    }
    if($cut === true)
    {
      $content = substr($content, 0, sfConfig::get('app_commentAdmin_max_length', 50));
      if(strlen($content) == sfConfig::get('app_commentAdmin_max_length', 50))
      {
        $content .= " ...";
      }
    }
    return $content;
  }

  public static function rewriteUrlForPage($uri, $page, $comment = true)
  {
    $exp = '/page=(\d+)/';
    if(preg_match($exp, $uri))
    {
      $uri = preg_replace($exp, 'page='.$page, $uri);
    }
    else
    {
      $uri .= (strstr($uri, "?") === false)? "?" : "&";
      $uri .= 'page='.$page;
    }
    if($comment === true)
    {
      $uri.="#comments";
    }
    return $uri;
  }
}
?>
