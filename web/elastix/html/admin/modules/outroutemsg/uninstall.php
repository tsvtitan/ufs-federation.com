<?php /* $Id: uninstall.php  $ */
//Copyright (C) 2009 Philippe Lindheimer 
//Copyright (C) 2009 Bandwidth.com
//
//This program is free software; you can redistribute it and/or
//modify it under the terms of the GNU General Public License
//as published by the Free Software Foundation version 2
//of the License.
//
//This program is distributed in the hope that it will be useful,
//but WITHOUT ANY WARRANTY; without even the implied warranty of
//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//GNU General Public License for more details.


sql("DROP TABLE IF EXISTS `outroutemsg`");

?>
