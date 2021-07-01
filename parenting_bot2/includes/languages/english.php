<?php
function lang($phrase) {
  static $lang = array(

    // Navbar Links

    'HOME_ADMIN'  => 'Home',
    'CATEGORIES'  => 'Categories',
    'articles'       => 'articles',
    'MEMBERS'     => 'Members',
    'COMMENTS'     => 'Comments',
    'STATISTICS'  => 'Statistics',
    'LOGS'        => 'Logs',
    '' => '',
    '' => '',
    '' => '',
    '' => '',
  );
  return $lang[$phrase];
}
 ?>
