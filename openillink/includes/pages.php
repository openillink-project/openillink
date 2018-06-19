<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// This file is part of OpenILLink software.
// Copyright (C) 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2015, 2016, 2017, 2018 CHUV.
// Original author(s): Pablo Iriarte <pablo@iriarte.ch>
// Other contributors are listed in the AUTHORS file at the top-level
// directory of this distribution.
// 
// OpenILLink is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
// 
// OpenILLink is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// 
// You should have received a copy of the GNU General Public License
// along with OpenILLink.  If not, see <http://www.gnu.org/licenses/>.
// 
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// Construct pages links
// parameters : &page, $total_pages, $total_results, $pageslinksurl

if ($total_results > 0)
{
//echo "<center><b>\n";

echo '<nav class="pagination is-centered is-small is-rounded" role="navigation" aria-label="pagination">';
// Build Previous Link

if($page > 1)
{
$prev = ($page - 1);
echo '<a href="' . $pageslinksurl . '&page='.$prev.'" class="pagination-previous"><span class="icon"><i class="fa fa-arrow-left"></i></span></a>';
}
$spage = $page - 7;
if ($spage <= 0)
$spage = 1;
$epage = $page + 7;
if ($epage > $total_pages)
$epage = $total_pages;
if($epage > 1)
{
echo '	<ul class="pagination-list">';

if($spage > 1) {
	echo '<li><a class="pagination-link" href="' . $pageslinksurl . '&page=1" aria-label="'.sprintf(__("Page %s"), 1).'">1</a></li>';
	if ($spage > 2) {
		echo '    <li><span class="pagination-ellipsis">&hellip;</span></li>';
	}

}
for($h = $spage ; $h <= $epage; $h++)
{
if(($page) == $h)
{
echo '<li><a class="pagination-link is-current" aria-label="'.sprintf(__("Page %s"), $h).'">'.$h.'</a></li>';
}
else
{
echo '<li><a class="pagination-link"  href="' . $pageslinksurl . '&page='.$h.'" aria-label="'.sprintf(__("Page %s"), $h).'">'.$h.'</a></li>';


}
}

if($epage < $total_pages) {
	if ($epage +1 < $total_pages) {
		echo '    <li><span class="pagination-ellipsis">&hellip;</span></li>';
	}
	echo '<li><a class="pagination-link" href="' . $pageslinksurl . '&page='.$total_pages.'" aria-label="'.sprintf(__("Page %s"), $total_pages).'">'.$total_pages.'</a></li>';

}

echo '</ul>';
}

// Build Next Link

if($page < $total_pages)
{
$next = ($page + 1);
echo '<a href="' . $pageslinksurl . '&page='.$next.'" class="pagination-next"><span class="icon"><i class="fa fa-arrow-right"></i></span></a>';
}
echo "</nav>";
}
?>
