<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>{{$title}}</title>
    <style>
        .btn{
            padding:10px;width:100px;background-color:#337ab7;border:1px solid transparent; color:white;margin-top:20px;border-radius:8px;font-size:16px;
        }
    h4 {
	    color: #fFDD39 !important;
	   }
    </style>
</head>

<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0"
      style="margin: 0pt auto; padding: 0px; background:#313A45;">
<table id="main" width="100%" height="100%" cellpadding="0" cellspacing="0" border="0"
       bgcolor="#313A45">
    <tbody>
    <tr>
        <td valign="top">
            <table class="innermain" cellpadding="0" width="580" cellspacing="0" border="0"
                   bgcolor="#313A45" align="center" style="margin:0 auto; table-layout: fixed;">
                <tbody>
                <!-- START of MAIL Content -->
                <tr>
                    <td colspan="4">
                        <!-- Logo start here -->
                        <table class="logo" width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tbody>
                            <tr>
                                <td colspan="2" height="30"></td>
                            </tr>
                            <tr>
                                <td valign="top" align="center"> <span style="font-family: -apple-system,BlinkMacSystemFont,&#39;Segoe UI&#39;,&#39;Roboto&#39;,&#39;Oxygen&#39;,&#39;Ubuntu&#39;,&#39;Cantarell&#39;,&#39;Fira Sans&#39;,&#39;Droid Sans&#39;,&#39;Helvetica Neue&#39;,sans-serif; color:#9EB0C9;">
                                    <a href="{{asset('/')}}" target="_blank" style="color:#9EB0C9 !important; text-decoration:none;">{!! $icon !!}</a></span>

                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" height="10"></td>
                            </tr>
                            </tbody>
                        </table>
                        <!-- Logo end here -->
                        <!-- Main CONTENT -->
                        <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff"
                               style="border-radius: 4px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                            <tbody>
                                <tr>
                                    <td height="40"></td>
                                </tr>
                                <tr style="font-family: -apple-system,BlinkMacSystemFont,&#39;Segoe UI&#39;,&#39;Roboto&#39;,&#39;Oxygen&#39;,&#39;Ubuntu&#39;,&#39;Cantarell&#39;,&#39;Fira Sans&#39;,&#39;Droid Sans&#39;,&#39;Helvetica Neue&#39;,sans-serif; color:#4E5C6E; font-size:14px; line-height:20px; margin-top:20px;">
                                    <td class="content" colspan="2" valign="top" align="left" style="padding-left:90px; padding-right:90px;padding-bottom:50px;">
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff">
                                            <tbody>
                                                <tr>
                                                    {!! $content !!}
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- Main CONTENT end here -->
                        <!-- PROMO column start here -->
                        <!-- Show mobile promo 75% of the time -->
                        <table id="promo" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top:20px;">
                            <tbody>
                            <tr>
                                <td colspan="2" height="20"></td>
                            </tr>
                            <tr>
                                <td colspan="2" height="20"></td>
                            </tr>
                            <tr>
                                <td colspan="2" height="20"></td>
                            </tr>
                            </tbody>
                        </table>
                        <!-- PROMO column end here -->
                        <!-- FOOTER start here -->
                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tbody>
                            <tr>
                                <td height="10">&nbsp;</td>
                            </tr>
                            <tr>
                                <td valign="top" align="center"> <span style="font-family: -apple-system,BlinkMacSystemFont,&#39;Segoe UI&#39;,&#39;Roboto&#39;,&#39;Oxygen&#39;,&#39;Ubuntu&#39;,&#39;Cantarell&#39;,&#39;Fira Sans&#39;,&#39;Droid Sans&#39;,&#39;Helvetica Neue&#39;,sans-serif; color:#9EB0C9; font-size:10px;">&copy;
                                    <a href="{{asset('/')}}" target="_blank" style="color:#9EB0C9 !important; text-decoration:none;">BSPKoperasi</a> {{date('Y')}}</span>

                                </td>
                            </tr>
                            <tr>
                                <td height="50">&nbsp;</td>
                            </tr>
                            </tbody>
                        </table>
                        <!-- FOOTER end here -->
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
</body>

</html>