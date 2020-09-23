var submitFlag = true;
var ajaxFlag = true;

/**
 * 提交
 * 
 * @param formName
 *            form名称
 * @param action
 *            formaction
 * @param target
 *            formtarget
 */
function doSubmit(formName, action, target)
{
	
	if(submitFlag == true)
	{
		submitFlag = false;
	}
	else
	{
		return;
	}
	
	var objForm;
	if(formName != null && formName != "undefined" && formName != "")
	{
		objForm = document.forms[formName];
	}
	else
	{
		objForm = document.forms[0];
	}

	if(action != null && action != "undefined" && action != "")
	{
		objForm.action = action + ".html";
	}

	if(target != null && target != "undefined" && target != "")
	{
		objForm.target = target;
	}
	else
	{
		objForm.target = "";
	}

	objForm.submit();
}

/**
 * 提交（不做2次提交check，用于wap页面和有下载、导出的页面）
 * 
 * @param formName
 *            form名称
 * @param action
 *            formaction
 * @param target
 *            formtarget
 */
function doSubmitNoCheck(formName, action, target)
{
	var objForm;
	if(formName != null && formName != "undefined" && formName != "")
	{
		objForm = document.forms[formName];
	}
	else
	{
		objForm = document.forms[0];
	}

	if(action != null && action != "undefined" && action != "")
	{
		objForm.action = action + ".html";
	}

	if(target != null && target != "undefined" && target != "")
	{
		objForm.target = target;
	}
	else
	{
		objForm.target = "";
	}

	objForm.submit();
}

function doAjax(obj, mask)
{
	if(mask == null)
	{
		mask = true;
	}
	
	if(mask)
	{
		if(ajaxFlag != true || submitFlag != true)
		{
			return;
		}
		
		ajaxFlag = false;
		obj.beforeSend = function(){$('.mask').show()};
		obj.complete = function()
		{
			if(submitFlag)
			{
			} 
			
			ajaxFlag = true;
		};
	}

	obj.type = "POST";
	obj.dataType = "json";
	
	$.ajax(obj)
}

/**
 * 翻页
 * 
 * @param formName
 *            form名称
 * @param action
 *            formaction
 * @param varName
 *            存当前页码的控件name
 * @param pageIndex
 *            页码
 */
function pageChange(formName, action, varName, pageIndex)
{
	document.getElementById(varName).value = pageIndex;
	document.forms[formName].action = action + ".html";
	document.forms[formName].submit();
}

function ajaxPageChange(url, pageindex, callback)
{
	$.ajax(
	{
		type : "POST",
		url : url,
		data :
		{
			pageindex : pageindex
		},
		success : function(data)
		{
			callback(data);
		}
	})
}

/**
 * 验证数据是否为正数
 * 
 * @param str
 * @returns
 */
function isPositiveNumber(str)
{
	if(str == null || str == "undefined" || str == "")
	{
		return true;
	}
	if(str == 0)
	{
		return false;
	}

	var reg = /^[+]?\d+$/;
	return str.match(reg) == null ? false : true;
}

/**
 * 验证数据是否为正数或0
 * 
 * @param str
 * @returns
 */
function isPositiveNumberOrZero(str)
{
	if(str == null || str == "undefined" || str == "")
	{
		return true;
	}

	var reg = /^[+]?\d+$/;
	return str.match(reg) == null ? false : true;
}

/**
 * 验证数据是否为负数
 * 
 * @param str
 * @returns
 */
function isNegativeNumber(str)
{
	if(str == null || str == "undefined" || str == "")
	{
		return true;
	}
	if(str == 0)
	{
		return false;
	}

	var reg = /^[-]\d+$/;
	return str.match(reg) == null ? false : true;
}

/**
 * 验证数据是否为负数或0
 * 
 * @param str
 * @returns
 */
function isNegativeNumberOrZero(str)
{
	if(str == null || str == "undefined" || str == "")
	{
		return true;
	}
	if(str == 0)
	{
		return true;
	}

	var reg = /^[-]\d+$/;
	return str.match(reg) == null ? false : true;
}

/**
 * 验证数据是否为数字
 * 
 * @param str
 * @returns
 */
function isNumber(str)
{
	if(str == null || str == "undefined" || str == "")
	{
		return true;
	}
	var reg = /^\d+$/;
	return str.match(reg) == null ? false : true;
}

/**
 * 验证数据是否为整数（可带正负）
 * 
 * @param str
 * @returns
 */
function isInteger(str)
{
	if(str == null || str == "undefined" || str == "")
	{
		return true;
	}
	var reg = /^[-\+]?\d+$/;
	return str.match(reg) == null ? false : true;
}

/**
 * 验证数据是否为浮点数（正数）
 * 
 * @param str
 * @returns
 */
function isPositiveDouble(str)
{
	if(str == null || str == "undefined" || str == "")
	{
		return true;
	}
	var reg = /^\d+(\.\d+)?$/;
	return str.match(reg) == null ? false : true;
}

/**
 * 验证数据是否为浮点数（可带正负）
 * 
 * @param str
 * @returns
 */
function isDouble(str)
{
	if(str == null || str == "undefined" || str == "")
	{
		return true;
	}
	var reg = /^[-\+]?\d+(\.\d+)?$/;
	return str.match(reg) == null ? false : true;
}

/**
 * 验证数据是否为英文
 * 
 * @param str
 * @returns
 */
function isEnglish(str)
{
	if(str == null || str == "undefined" || str == "")
	{
		return true;
	}
	var reg = /^[A-Za-z]+$/;
	return str.match(reg) == null ? false : true;
}

/**
 * 验证数据是否为英文或数字
 * 
 * @param str
 * @returns
 */
function isEnglishOrNumber(str)
{
	if(str == null || str == "undefined" || str == "")
	{
		return true;
	}
	var reg = /^[A-Za-z\d]+$/;
	return str.match(reg) == null ? false : true;
}

/**
 * 验证数据是否为英文或数字或下划线
 * 
 * @param str
 * @returns
 */
function isEnglishOrNumberOrUnderline(str)
{
	if(str == null || str == "undefined" || str == "")
	{
		return true;
	}
	var reg = /^\w+$/;
	return str.match(reg) == null ? false : true;
}

/**
 * 验证是否为手机号
 * 
 * @param str
 * @returns
 */
function isMobile(str)
{
	if(str == null || str == "undefined" || str == "")
	{
		return true;
	}
	var reg = /^(1)\d{10}$/;
	return str.match(reg) == null ? false : true;
}

/**
 * 验证数据是否为电话号码（3-4位区号+7-8位号码，区号可以没）
 * 
 * @param str
 * @returns
 */
function isTel(str)
{
	if(str == null || str == "undefined" || str == "")
	{
		return true;
	}
	var reg = /^(\d{3,4}-)?\d{7,8}$/;
	return str.match(reg) == null ? false : true;
}

/**
 * 验证数据是否为邮编
 * 
 * @param str
 * @returns
 */
function isZipcode(str)
{
	if(str == null || str == "undefined" || str == "")
	{
		return true;
	}
	var reg = /^\d{6}$/;
	return str.match(reg) == null ? false : true;
}

/**
 * 验证数据是否为Email
 * 
 * @param str
 * @returns
 */
function isEmail(str)
{
	if(str == null || str == "undefined" || str == "")
	{
		return true;
	}
	var reg = /^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]+$/;
	return str.match(reg) == null ? false : true;
}

/**
 * 验证数据是否为日期（yyyy-MM-dd）
 * 
 * @param str
 * @returns
 */
function isDate(str, dilimeter)
{
	if(str == null || str == "undefined" || str == "")
	{
		return true;
	}
	if(dilimeter == null || dilimeter == "undefined" || dilimeter == "")
	{
		dilimeter = "-";
	}
	var tempy = "";
	var tempm = "";
	var tempd = "";
	var tempArray;
	if(str.length != 10)
	{
		return false;
	}
	tempArray = str.split(dilimeter);
	if(tempArray.length != 3)
	{
		return false;
	}
	else if(tempArray[0].length != 4 || tempArray[1].length != 2
			|| tempArray[2].length != 2)
	{
		return false;
	}
	else
	{
		tempy = tempArray[0];
		tempm = tempArray[1];
		tempd = tempArray[2];
	}
	if(isNaN(tempy))
	{
		return false;
	}
	if(isNaN(tempm))
	{
		return false;
	}
	if(isNaN(tempd))
	{
		return false;
	}
	var tDateString = tempy + "/" + tempm + "/" + tempd;
	var tempDate = new Date(tDateString);

	if(tempDate.getFullYear() != tempy)
	{
		return false;
	}
	if(tempDate.getMonth() != tempm - 1)
	{
		return false;
	}
	if(tempDate.getDate() != tempd)
	{
		return false;
	}
	return true;
}

/**
 * 验证数据是否为日期时间（yyyy-MM-dd HH:mm:ss）
 * 
 * @param str
 * @returns
 */
function isDatetime(str, dilimeter)
{
	if(str == null || str == "undefined" || str == "")
	{
		return true;
	}
	if(str.length != 19)
	{
		return false;
	}

	var array = str.split(" ");
	var tempdate = "";
	var temptime = "";
	if(array.length != 2)
	{
		return false;
	}
	else if(array[0].length != 10 || array[1].length != 8)
	{
		return false;
	}
	else
	{
		tempdate = array[0];
		temptime = array[1];
	}

	if(dilimeter == null || dilimeter == "undefined" || dilimeter == "")
	{
		dilimeter = "-";
	}
	var tempy = "";
	var tempm = "";
	var tempd = "";
	var tempArray;

	tempArray = tempdate.split(dilimeter);
	if(tempArray.length != 3)
	{
		return false;
	}
	else if(tempArray[0].length != 4 || tempArray[1].length != 2
			|| tempArray[2].length != 2)
	{
		return false;
	}
	else
	{
		tempy = tempArray[0];
		tempm = tempArray[1];
		tempd = tempArray[2];
	}
	if(isNaN(tempy))
	{
		return false;
	}
	if(isNaN(tempm))
	{
		return false;
	}
	if(isNaN(tempd))
	{
		return false;
	}
	var tDateString = tempy + "/" + tempm + "/" + tempd;
	var tempDate = new Date(tDateString);

	if(tempDate.getFullYear() != tempy)
	{
		return false;
	}
	if(tempDate.getMonth() != tempm - 1)
	{
		return false;
	}
	if(tempDate.getDate() != tempd)
	{
		return false;
	}

	tempArray = temptime.split(':');
	var h = "";
	var m = "";
	var s = "";
	if(tempArray.length != 3)
	{
		return false;
	}
	else if(tempArray[0].length != 2 || tempArray[1].length != 2
			|| tempArray[2].length != 2)
	{
		return false;
	}
	else
	{
		h = tempArray[0];
		m = tempArray[1];
		s = tempArray[2];
	}
	if(isNaN(h) || h < 0 || h > 23)
	{
		return false;
	}
	if(isNaN(m) || m < 0 || m > 59)
	{
		return false;
	}
	if(isNaN(s) || s < 0 || s > 59)
	{
		return false;
	}

	return true;
}

/**
 * 验证数据是否为身份证号码
 * 
 * @param str
 * @returns
 */
function isIdentificationCard(str)
{
	if(str == null || str == "undefined" || str == "")
	{
		return true;
	}
	if(str.length != 15 && str.length != 18)
	{
		return false;
	}

	// 15位
	if(str.length == 15)
	{
		if(!isNumber(str))
		{
			return false;
		}
		var birth = "19" + str.substr(6, 2) + "-" + str.substr(8, 2) + "-"
				+ str.substr(10, 2);
		if(!isDate(birth))
		{
			return false;
		}
	}
	// 18位
	else
	{
		// 最后一位校验位
		var validateCode = str.substr(17, 1);
		if(validateCode == "x")
		{
			validateCode = "X";
		}
		if(!isNumber(str.substr(0, 17))
				|| (!isNumber(validateCode) && validateCode != "X"))
		{
			return false;
		}
		var birth = str.substr(6, 4) + "-" + str.substr(10, 2) + "-"
				+ str.substr(12, 2);
		if(!isDate(birth))
		{
			return false;
		}

		var lSum = 0;
		var nNum = 0;
		var Wi = new Array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
		for(var i = 0; i < 17; i++)
		{
			nNum = parseInt(str.charAt(i));
			lSum += nNum * Wi[i];
		}

		if(validateCode == "X")
		{
			lSum += 10;
		}
		else
		{
			lSum += parseInt(validateCode);
		}

		if((lSum % 11) != 1)
		{
			return false;
		}
	}

	return true;
}

// 处理键盘事件 禁止后退键（Backspace）密码或单行、多行文本框除外
function banBackSpace(e)
{
	// alert(event.keyCode)
	var ev = e || window.event;// 获取event对象
	var obj = ev.target || ev.srcElement;// 获取事件源
	var t = obj.type || obj.getAttribute('type');// 获取事件源类型
	// 获取作为判断条件的事件类型
	var vReadOnly = obj.readOnly;
	var vDisabled = obj.disabled;
	// 处理undefined值情况
	vReadOnly = (vReadOnly == undefined) ? false : vReadOnly;
	vDisabled = (vDisabled == undefined) ? true : vDisabled;
	// 当敲Backspace键时，事件源类型为密码或单行、多行文本的，
	// 并且readOnly属性为true或disabled属性为true的，则退格键失效
	var flag1 = ev.keyCode == 8
			&& (t == "password" || t == "text" || t == "textarea")
			&& (vReadOnly == true || vDisabled == true);
	// 当敲Backspace键时，事件源类型非密码或单行、多行文本的，则退格键失效
	var flag2 = ev.keyCode == 8 && t != "password" && t != "text"
			&& t != "textarea";
	// 判断
	if(flag2 || flag1)
		event.returnValue = false;// 这里如果写 return false 无法实现效果
}

// 判断str为空或null
function isNullOrEmpty(str)
{
	if(str == null || str == '')
	{
		return true;
	}

	return false;
}

// trim
String.prototype.Trim = function()
{
	return this.replace(/(^\s*)|(\s*$)/g, "");
}
String.prototype.LTrim = function()
{
	return this.replace(/(^\s*)/g, "");
}
String.prototype.RTrim = function()
{
	return this.replace(/(\s*$)/g, "");
}
