<?php
	mb_internal_encoding( 'UTF-8' );
	mb_regex_encoding('utf-8');
	
	$my_array = unserialize(urldecode(stripslashes($_POST['mytext'])));
	print_r ($my_array);


	
	print_r($_POST);

	
	
	if($_POST['mytext']) {
		//if we recieved HTML variables and the array is filled, call process form
		
		//clean up mytext
		$my_array = unserialize(urldecode(stripslashes($_POST['mytext'])));
		print_r($my_array);
		//process and store data
		//process_form($my_array);
	} else {
		generate_form ();
	}
function generate_form () {
	//echo the page from the header/meta tags to beginning of <body>
	?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
	<meta http-equiv="content-language" content="ko"> 
<meta http-equiv="content-type" content="text/html; charset=utf8">
<html><head><title>Translator Learning</title>
<script type="text/javascript">
<!--

function serialize(o) {
    var p = 0, sb = [], ht = [], hv = 1;
    var classname = function(o) {
        if (typeof(o) == "undefined" || typeof(o.constructor) == "undefined") return '';
        var c = o.constructor.toString();
        c = utf16to8(c.substr(0, c.indexOf('(')).replace(/(^\s*function\s*)|(\s*$)/ig, ''));
        return ((c == '') ? 'Object' : c);
    };
    var is_int = function(n) {
        var s = n.toString(), l = s.length;
        if (l > 11) return false;
        for (var i = (s.charAt(0) == '-') ? 1 : 0; i < l; i++) {
            switch (s.charAt(i)) {
                case '0':
                case '1':
                case '2':
                case '3':
                case '4':
                case '5':
                case '6':
                case '7':
                case '8':
                case '9': break;
                default : return false;
            }
        }
        return !(n < -2147483648 || n > 2147483647);
    };
    var in_ht = function(o) {
        for (k in ht) if (ht[k] === o) return k;
        return false;
    };
    var ser_null = function() {
        sb[p++] = 'N;';
    };
    var ser_boolean = function(b) {
        sb[p++] = (b ? 'b:1;' : 'b:0;');
    };
    var ser_integer = function(i) {
        sb[p++] = 'i:' + i + ';';
    };
    var ser_double = function(d) {
        if (isNaN(d)) d = 'NAN';
        else if (d == Number.POSITIVE_INFINITY) d = 'INF';
        else if (d == Number.NEGATIVE_INFINITY) d = '-INF';
        sb[p++] = 'd:' + d + ';';
    };
    var ser_string = function(s) {
        var utf8 = utf16to8(s);
        sb[p++] = 's:' + utf8.length + ':"';
        sb[p++] = utf8;
        sb[p++] = '";';
    };
    var ser_array = function(a) {
        sb[p++] = 'a:';
        var lp = p;
        sb[p++] = 0;
        sb[p++] = ':{';
        for (var k in a) {
            if (typeof(a[k]) != 'function') {
                is_int(k) ? ser_integer(k) : ser_string(k);
                __serialize(a[k]);
                sb[lp]++;
            }
        }
        sb[p++] = '}';
    };
    var ser_object = function(o) {
        var cn = classname(o);
        if (cn == '') ser_null();
        else if (typeof(o.serialize) != 'function') {
            sb[p++] = 'O:' + cn.length + ':"';
            sb[p++] = cn;
            sb[p++] = '":';
            var lp = p;
            sb[p++] = 0;
            sb[p++] = ':{';
            if (typeof(o.__sleep) == 'function') {
                var a = o.__sleep();
                for (var kk in a) {
                    ser_string(a[kk]);
                    __serialize(o[a[kk]]);
                    sb[lp]++;
                }
            }
            else {
                for (var k in o) {
                    if (typeof(o[k]) != 'function') {
                        ser_string(k);
                        __serialize(o[k]);
                        sb[lp]++;
                    }
                }
            }
            sb[p++] = '}';
        }
        else {
            var cs = o.serialize();
            sb[p++] = 'C:' + cn.length + ':"';
            sb[p++] = cn;
            sb[p++] = '":' + cs.length + ':{';
            sb[p++] = cs;
            sb[p++] = "}";
        }
    };
    var ser_pointref = function(R) {
        sb[p++] = "R:" + R + ";";
    };
    var ser_ref = function(r) {
        sb[p++] = "r:" + r + ";";
    };
    var __serialize = function(o) {
        if (o == null || o.constructor == Function) {
            hv++;
            ser_null();
        }
        else switch (o.constructor) {
            case Boolean: {
                hv++;
                ser_boolean(o);
                break;
            }
            case Number: {
                hv++;
                is_int(o) ? ser_integer(o) : ser_double(o);
                break;
            }
            case String: {
                hv++;
                ser_string(o);
                break;
            }
/*@cc_on @*/
/*@if (@_jscript)
            case VBArray: {
                o = o.toArray();
            }
@end @*/
            case Array: {
                var r = in_ht(o);
                if (r) {
                    ser_pointref(r);
                }
                else {
                    ht[hv++] = o;
                    ser_array(o);
                }
                break;
            }
            default: {
                var r = in_ht(o);
                if (r) {
                    hv++;
                    ser_ref(r);
                }
                else {
                    ht[hv++] = o;
                    ser_object(o);
                }
                break;
            }
        }
    };
    __serialize(o);
    return sb.join('');
}

function unserialize(ss) {
    var p = 0, ht = [], hv = 1; r = null;
    var unser_null = function() {
        p++;
        return null;
    };
    var unser_boolean = function() {
        p++;
        var b = (ss.charAt(p++) == '1');
        p++;
        return b;
    };
    var unser_integer = function() {
        p++;
        var i = parseInt(ss.substring(p, p = ss.indexOf(';', p)));
        p++;
        return i;
    };
    var unser_double = function() {
        p++;
        var d = ss.substring(p, p = ss.indexOf(';', p));
        switch (d) {
            case 'NAN': d = NaN; break;
            case 'INF': d = Number.POSITIVE_INFINITY; break;
            case '-INF': d = Number.NEGATIVE_INFINITY; break;
            default: d = parseFloat(d);
        }
        p++;
        return d;
    };
    var unser_string = function() {
        p++;
        var l = parseInt(ss.substring(p, p = ss.indexOf(':', p)));
        p += 2;
        var s = utf8to16(ss.substring(p, p += l));
        p += 2;
        return s;
    };
    var unser_array = function() {
        p++;
        var n = parseInt(ss.substring(p, p = ss.indexOf(':', p)));
        p += 2;
        var a = [];
        ht[hv++] = a;
        for (var i = 0; i < n; i++) {
            var k;
            switch (ss.charAt(p++)) {
                case 'i': k = unser_integer(); break;
                case 's': k = unser_string(); break;
                case 'U': k = unser_unicode_string(); break;
                default: return false;
            }
            a[k] = __unserialize();
        }
        p++;
        return a;
    };
    var unser_object = function() {
        p++;
        var l = parseInt(ss.substring(p, p = ss.indexOf(':', p)));
        p += 2;
        var cn = utf8to16(ss.substring(p, p += l));
        p += 2;
        var n = parseInt(ss.substring(p, p = ss.indexOf(':', p)));
        p += 2;
        if (eval(['typeof(', cn, ') == "undefined"'].join(''))) {
            eval(['function ', cn, '(){}'].join(''));
        }
        var o = eval(['new ', cn, '()'].join(''));
        ht[hv++] = o;
        for (var i = 0; i < n; i++) {
            var k;
            switch (ss.charAt(p++)) {
                case 's': k = unser_string(); break;
                case 'U': k = unser_unicode_string(); break;
                default: return false;
            }
            if (k.charAt(0) == '\0') {
                k = k.substring(k.indexOf('\0', 1) + 1, k.length);
            }
            o[k] = __unserialize();
        }
        p++;
        if (typeof(o.__wakeup) == 'function') o.__wakeup();
        return o;
    };
    var unser_custom_object = function() {
        p++;
        var l = parseInt(ss.substring(p, p = ss.indexOf(':', p)));
        p += 2;
        var cn = utf8to16(ss.substring(p, p += l));
        p += 2;
        var n = parseInt(ss.substring(p, p = ss.indexOf(':', p)));
        p += 2;
        if (eval(['typeof(', cn, ') == "undefined"'].join(''))) {
            eval(['function ', cn, '(){}'].join(''));
        }
        var o = eval(['new ', cn, '()'].join(''));
        ht[hv++] = o;
        if (typeof(o.unserialize) != 'function') p += n;
        else o.unserialize(ss.substring(p, p += n));
        p++;
        return o;
    };
    var unser_unicode_string = function() {
        p++;
        var l = parseInt(ss.substring(p, p = ss.indexOf(':', p)));
        p += 2;
        var sb = [];
        for (var i = 0; i < l; i++) {
            if ((sb[i] = ss.charAt(p++)) == '\\') {
                sb[i] = String.fromCharCode(parseInt(ss.substring(p, p += 4), 16));
            }
        }
        p += 2;
        return sb.join('');
    };
    var unser_ref = function() {
        p++;
        var r = parseInt(ss.substring(p, p = ss.indexOf(';', p)));
        p++;
        return ht[r];
    };
    var __unserialize = function() {
        switch (ss.charAt(p++)) {
            case 'N': return ht[hv++] = unser_null();
            case 'b': return ht[hv++] = unser_boolean();
            case 'i': return ht[hv++] = unser_integer();
            case 'd': return ht[hv++] = unser_double();
            case 's': return ht[hv++] = unser_string();
            case 'U': return ht[hv++] = unser_unicode_string();
            case 'r': return ht[hv++] = unser_ref();
            case 'a': return unser_array();
            case 'O': return unser_object();
            case 'C': return unser_custom_object();
            case 'R': return unser_ref();
            default: return false;
        }
    };
    return __unserialize();
}

function utf16to8(str) {
    var out, i, j, len, c, c2;
    out = [];
    len = str.length;
    for (i = 0, j = 0; i < len; i++, j++) {
        c = str.charCodeAt(i);
        if (c <= 0x7f) {
            out[j] = str.charAt(i);
        }
        else if (c <= 0x7ff) {
            out[j] = String.fromCharCode(0xc0 | (c >>> 6),
                                         0x80 | (c & 0x3f));
        }
        else if (c < 0xd800 || c > 0xdfff) {
            out[j] = String.fromCharCode(0xe0 | (c >>> 12),
                                         0x80 | ((c >>> 6) & 0x3f),
                                         0x80 | (c & 0x3f));
        }
        else {
            if (++i < len) {
                c2 = str.charCodeAt(i);
                if (c <= 0xdbff && 0xdc00 <= c2 && c2 <= 0xdfff) {
                    c = ((c & 0x03ff) << 10 | (c2 & 0x03ff)) + 0x010000;
                    if (0x010000 <= c && c <= 0x10ffff) {
                        out[j] = String.fromCharCode(0xf0 | ((c >>> 18) & 0x3f),
                                                     0x80 | ((c >>> 12) & 0x3f),
                                                     0x80 | ((c >>> 6) & 0x3f),
                                                     0x80 | (c & 0x3f));
                    }
                    else {
                       out[j] = '?';
                    }
                }
                else {
                    i--;
                    out[j] = '?';
                }
            }
            else {
                i--;
                out[j] = '?';
            }
        }
    }
    return out.join('');
}

function utf8to16(str) {
    var out, i, j, len, c, c2, c3, c4, s;

    out = [];
    len = str.length;
    i = j = 0;
    while (i < len) {
        c = str.charCodeAt(i++);
        switch (c >> 4) { 
            case 0: case 1: case 2: case 3: case 4: case 5: case 6: case 7:
            // 0xxx xxxx
            out[j++] = str.charAt(i - 1);
            break;
            case 12: case 13:
            // 110x xxxx   10xx xxxx
            c2 = str.charCodeAt(i++);
            out[j++] = String.fromCharCode(((c  & 0x1f) << 6) |
                                            (c2 & 0x3f));
            break;
            case 14:
            // 1110 xxxx  10xx xxxx  10xx xxxx
            c2 = str.charCodeAt(i++);
            c3 = str.charCodeAt(i++);
            out[j++] = String.fromCharCode(((c  & 0x0f) << 12) |
                                           ((c2 & 0x3f) <<  6) |
                                            (c3 & 0x3f));
            break;
            case 15:
            switch (c & 0xf) {
                case 0: case 1: case 2: case 3: case 4: case 5: case 6: case 7:
                // 1111 0xxx  10xx xxxx  10xx xxxx  10xx xxxx
                c2 = str.charCodeAt(i++);
                c3 = str.charCodeAt(i++);
                c4 = str.charCodeAt(i++);
                s = ((c  & 0x07) << 18) |
                    ((c2 & 0x3f) << 12) |
                    ((c3 & 0x3f) <<  6) |
                     (c4 & 0x3f) - 0x10000;
                if (0 <= s && s <= 0xfffff) {
                    out[j] = String.fromCharCode(((s >>> 10) & 0x03ff) | 0xd800,
                                                  (s         & 0x03ff) | 0xdc00);
                }
                else {
                    out[j] = '?';
                }
                break;
                case 8: case 9: case 10: case 11:
                // 1111 10xx  10xx xxxx  10xx xxxx  10xx xxxx  10xx xxxx
                i+=4;
                out[j] = '?';
                break;
                case 12: case 13:
                // 1111 110x  10xx xxxx  10xx xxxx  10xx xxxx  10xx xxxx  10xx xxxx
                i+=5;
                out[j] = '?';
                break;
            }
        }
        j++;
    }
    return out.join('');
}
	var dataString = ["ㅁ재ㅑㅓㅁㄴㅇㄹ","abcd"];
	
	function sendForm()
{
	document.hiddenform.mytext.value = dataString;
	document.hiddenform.submit(); 
}
	function fillForm() {
		document.hiddenform.mytext.value = serialize(dataString);
		alert(dataString[0]);
	}
//-->
</script>
</head>
<body>
	<form name="hiddenform" method="POST" accept-charset="utf-8" action="<?php echo $_SERVER['PHP_SELF']; ?>"> 
		<input  type="text" name="mytext" value="ㄷㅁㅇㅅ">
		<input type="text" name="Korean" value="ㅁㄴㅇㄱ">
		
	</form>
	<a href="#" onClick="sendForm();"> Clickme!</a>
	<a href="#" onClick="fillForm();"> ClickFILL</a>
	</body>
	</html>
	<?php } ?>