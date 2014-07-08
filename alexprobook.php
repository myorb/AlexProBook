<?php
/*
    Plugin Name: Alex Pro Book
    Plugin URI: https://github.com/myorb/AlexProBook
    Description: An address book.
    Version: 1.0
    Author: Alexander V. Shalaiev
    Author URI: //github.com/myorb

    Copyright 2014  Alex V. Shalaiev  (email : itmedved@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define('ALEX_PRO_FILE',  __FILE__);
define('ALEX_PRO_BOOK_PATH', plugin_dir_path( __FILE__));

require ALEX_PRO_BOOK_PATH . 'includes/alexprobook.php';

new AlexProBook;


