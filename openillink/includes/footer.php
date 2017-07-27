<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// This file is part of OpenILLink software.
// Copyright (C) 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2015, 2016, 2017 CHUV.
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
// Footer common to all pages
//
echo "</div>\n";
echo "</div>\n";
echo "  <div class=\"footerArea\">\n";
echo "    <div class=\"footer\" id=\"footer\">\n";
echo __("Powered by") . " <a href=\"https://github.com/openillink-project\" target=\"_blank\">OpenILLink</a><br />" . "\n";
echo "&copy; <a href=\"http://www.pablog.ch\" target=\"_blank\">Pablo Iriarte</a>,  <a href=\"http://jankrause.net\" target=\"_blank\">Jan Krause</a>, <a href=\"http://www.bium.ch\" target=\"_blank\">BiUM</a>/<a href=\"http://www.chuv.ch\">CHUV</a> Lausanne, <a href=\"http://www.unige.ch/medecine/bibliotheque/\" target=\"_blank\">BFM</a>, <a href=\"http://www.unige.ch\" target=\"_blank\">UNIGE</a> Genève" . "\n";
echo $configteam[$lang] . "\n";
echo "    </div>\n";
echo "    <div class=\"info_dbg\" id=\"info_info_dbg\">\n";
//apd_set_pprof_trace();
echo "    </div>\n";
echo "  </div>\n";
echo "</div>\n";
echo "</body>\n";
echo "</html>";
?>
