//ajax根据大区联动小区
function ajaxGetSmallAreaOption(url,ctrlId,province_code,firstOption,default_value,callBack,callBackParam)
{

    if(province_code == '')
    {
        var data = "<option value=''>" + firstOption + "</option>";
        $("#" + ctrlId).html(data);

        if(!isNullOrEmpty(callBack))
        {
            if(callBackParam == null)
            {
                callBack();
            }
            else
            {
                callBack(callBackParam);
            }
        }
    }
    else
    {

        $.ajax({
            type:"POST",
            url:url + "/ajaxGetSmallArea",
            data:{province_code:province_code
                ,firstOption:firstOption
                ,default_value:default_value
            },
            success:function(data){
                $("#" + ctrlId).html(data);

                if(!isNullOrEmpty(callBack))
                {
                    if(callBackParam == null)
                    {
                        callBack();
                    }
                    else
                    {
                        callBack(callBackParam);
                    }
                }
            }
        });
    }
}

//ajax根据地区联动地点
function ajaxGetPlaceOption(url,ctrlId,area_small_code,firstOption,default_value,callBack,callBackParam,type,transport_flg)
{

    if(area_small_code == '')
    {
        var data = "<option value=''>" + firstOption + "</option>";
        $("#" + ctrlId).html(data);

        if(!isNullOrEmpty(callBack))
        {
            if(callBackParam == null)
            {
                callBack();
            }
            else
            {
                callBack(callBackParam);
            }
        }
    }
    else
    {

        $.ajax({
            type:"POST",
            url:url + "/ajaxGetFranchiser",
            data:{area_small_code:area_small_code
                ,firstOption:firstOption
                ,type:type
                ,default_value:default_value
                ,transport_flg:transport_flg
            },
            success:function(data){
                $("#" + ctrlId).html(data);
                if(!isNullOrEmpty(callBack))
                {
                    if(callBackParam == null)
                    {
                        callBack();
                    }
                    else
                    {
                        callBack(callBackParam);
                    }
                }
            }
        });
    }
}

//ajax根据药品规格联动药品
function ajaxGetMedicineOption(url,ctrlId,type,firstOption,default_value,callBack,callBackParam)
{

    if(type == '')
    {
        var data = "<option value=''>" + firstOption + "</option>";
        $("#" + ctrlId).html(data);

        if(!isNullOrEmpty(callBack))
        {
            if(callBackParam == null)
            {
                callBack();
            }
            else
            {
                callBack(callBackParam);
            }
        }
    }
    else
    {
        $.ajax({
            type:"POST",
            url:url + "/ajaxGetMedicine",
            data:{firstOption:firstOption,
                type:type,
                default_value: default_value
            },
            success:function(data){
                $("#" + ctrlId).html(data);

                if(!isNullOrEmpty(callBack))
                {
                    if(callBackParam == null)
                    {
                        callBack();
                    }
                    else
                    {
                        callBack(callBackParam);
                    }
                }
            }
        });
    }
}

// 浮点数相加
// last modify by jichenghan 2016-07-27
function accAdd(arg1, arg2, n) {
    if(!n) n = 4;
    var r1, r2, m, c;
    try { r1 = arg1.toString().split(".")[1].length } catch (e) { r1 = 0 }
    try { r2 = arg2.toString().split(".")[1].length } catch (e) { r2 = 0 }
    c = Math.abs(r1 - r2);
    m = Math.pow(10, Math.max(r1, r2))
    if (c > 0) {
        var cm = Math.pow(10, c);
        if (r1 > r2) {
            arg1 = Number(arg1.toString().replace(".", ""));
            arg2 = Number(arg2.toString().replace(".", "")) * cm;
        }
        else {
            arg1 = Number(arg1.toString().replace(".", "")) * cm;
            arg2 = Number(arg2.toString().replace(".", ""));
        }
    }
    else {
        arg1 = Number(arg1.toString().replace(".", ""));
        arg2 = Number(arg2.toString().replace(".", ""));
    }
    var result = ((arg1 + arg2) / m).toFixed(n + 1);
    return result.substring(0, result.lastIndexOf('.') + n + 1);
}

//浮点数相减 last modify by jichenghan
function accSub(arg1,arg2,n){
    var r1,r2,m;
    try{r1=arg1.toString().split(".")[1].length}catch(e){r1=0}
    try{r2=arg2.toString().split(".")[1].length}catch(e){r2=0}
    m=Math.pow(10,Math.max(r1,r2));
    //动态控制精度
    if(!n) n = 4;
    var result = ((arg1*m-arg2*m)/m).toFixed(n + 1);
    return result.substring(0, result.lastIndexOf('.') + n + 1);
}

// 浮点数相乘
// last modify by jichenghan 2016-07-27
function accMul(arg1, arg2, n)
{
    if(!n) n = 4;
    var m = 0, s1 = arg1.toString(), s2 = arg2.toString();
    try { m += s1.split(".")[1].length } catch (e) { }
    try { m += s2.split(".")[1].length } catch (e) { }
    var result = (Number(s1.replace(".", "")) * Number(s2.replace(".", "")) / Math.pow(10, m)).toFixed(n + 1);
    return result.substring(0, result.lastIndexOf('.') + n + 1);
}

// 浮点数相除
// last modify by jichenghan 2016-07-27
function accDiv(arg1, arg2, n)
{
    if(!n) n = 4;
    var t1 = 0, t2 = 0, r1, r2;
    try { t1 = arg1.toString().split(".")[1].length } catch (e) { }
    try { t2 = arg2.toString().split(".")[1].length } catch (e) { }
    with (Math) {
        r1 = Number(arg1.toString().replace(".", ""));
        r2 = Number(arg2.toString().replace(".", ""));
        var result = ((r1 / r2) * pow(10, t2 - t1)).toFixed(n + 1);
        return result.substring(0, result.lastIndexOf('.') + n + 1);
    }
}


/**
 * ajax获取人员数据
 * @param url
 * @param small_code
 * @param ctrlId
 * @param firstOption
 * @param user_type 用户type 空为所有用户 1：销售 2：商业负责人
 * @param default_value
 */
function ajaxGetUserOption(url,small_code,ctrlId,firstOption,user_type, default_value)
{
    if(small_code == '')
    {
        if($.isArray(ctrlId))
        {
            for(var i=0;i<ctrlId.length;i++)
            {
                $("#" + ctrlId[i]).html("<option value=''>" + firstOption + "</option>");
            }
        }
        else
        {
            $("#" + ctrlId).html("<option value=''>" + firstOption + "</option>");
        }
    }
    else
    {
        $.ajax({
            type:"POST",
            url:url + "/ajaxGetUser.html",
            async: false,
            data:{small_code:small_code
                ,firstOption:firstOption
                ,default_value:default_value
                ,user_type:user_type
            },
            success:function(data){
                if($.isArray(ctrlId))
                {
                    for(var i=0;i<ctrlId.length;i++)
                    {
                        $("#" + ctrlId[i]).html(data);
                    }
                }
                else
                {
                    $("#" + ctrlId).html(data);
                }

            }
        });
    }
}

//ajax模糊查询地点
function ajaxGetPlaceFuzzy(url, ctrlIdName, ctrlIdValue,param, callBack, callBackParam)
{
    var ctrl = "#" + ctrlIdName;
    $(ctrl).autocomplete({
        source: url + '/ajaxPlaceFuzzy.html',
        select: function( event, ui ) {
            if(!isNullOrEmpty(ctrlIdValue))
            {
                if(ui.item)
                {
                    $("#" + ctrlIdValue).val(ui.item.id);
                }
            }

            if(!isNullOrEmpty(callBack))
            {

                if(callBackParam == null)
                {
                    callBack();
                }
                else
                {
                    callBack(callBackParam);
                }
            }
        }
    })
}

//ajax模糊查询地点
function ajaxGetPlaceFuzzyForHospital(url, ctrlIdName, ctrlIdValue,param, callBack, callBackParam)
{
    var ctrl = "#" + ctrlIdName;
    $(ctrl).autocomplete({
        source: url + '/ajaxGetFuzzyForHospital.html',
        select: function( event, ui ) {
            if(!isNullOrEmpty(ctrlIdValue))
            {
                if(ui.item)
                {
                    $("#" + ctrlIdName).val(ui.item.hospital_name);
                    $("#" + ctrlIdValue).val(ui.item.id);

                    if($("#province_name").length>0)
                    {
                        if(ui.item.hasOwnProperty('province_name'))
                        {
                            $("#province_name").val(ui.item.province_name);
                        }
                    }

                    if($("#city_name").length>0)
                    {
                        if(ui.item.hasOwnProperty('city_name'))
                        {
                            $("#city_name").val(ui.item.city_name);
                        }
                    }
                }
            }

            if(!isNullOrEmpty(callBack))
            {

                if(callBackParam == null)
                {
                    callBack();
                }
                else
                {
                    callBack(callBackParam);
                }
            }
        }
    })
}

//ajax模糊查询地点
function ajaxGetFuzzyForDoctor(url, ctrlIdName, ctrlIdValue,param, callBack, callBackParam)
{
    var ctrl = "#" + ctrlIdName;
    $(ctrl).autocomplete({
        source: url + '/ajaxGetFuzzyForDoctor.html',
        select: function( event, ui ) {
            if(!isNullOrEmpty(ctrlIdValue))
            {
                if(ui.item)
                {
                    $("#" + ctrlIdName).val(ui.item.doctor_name);
                    $("#" + ctrlIdValue).val(ui.item.id);


                    if($("#province_code").length>0)
                    {
                        if(ui.item.hasOwnProperty('province_code'))
                        {
                            $('#province_code').val(ui.item.province_code);
                        }
                    }

                    if($("#city_code").length>0)
                    {
                        if (ui.item.hasOwnProperty('city_code')) {
                            $('#city_code').val(ui.item.city_code);
                        }
                    }


                    if($("#hospital_id").length>0)
                    {
                        if(ui.item.hasOwnProperty('hospital_id'))
                        {
                            $('#hospital_id').val(ui.item.hospital_id);
                        }
                    }


                    if($("#hospital_name").length>0)
                    {
                        if(ui.item.hasOwnProperty('hospital_name'))
                        {
                            $('#hospital_name').html(ui.item.hospital_name);
                        }
                    }


                    if($("#department_id").length>0)
                    {
                        if(ui.item.hasOwnProperty('department_id'))
                        {
                            $('#department_id').val(ui.item.department_id);
                        }
                    }


                    if($("#department_name").length>0)
                    {
                        if(ui.item.hasOwnProperty('department_name'))
                        {
                            $('#department_name').html(ui.item.department_name);
                        }
                    }


                    if($("#district_name").length>0)
                    {
                        if(ui.item.hasOwnProperty('province_name') && ui.item.hasOwnProperty('city_name'))
                        {

                            $('#district_name').html(ui.item.province_name+ui.item.city_name);
                        }
                    }


                    if($("#qualifications").length>0)
                    {
                        if(ui.item.hasOwnProperty('qualifications'))
                        {
                            $('#qualifications').html(ui.item.qualifications);
                        }
                    }


                    if($("#duty").length>0)
                    {
                        if(ui.item.hasOwnProperty('duty'))
                        {
                            $('#duty').html(ui.item.duty);
                        }
                    }


                    if($("#speaker_level").length>0)
                    {
                        if(ui.item.hasOwnProperty('speaker_level'))
                        {
                            $('#level').val(ui.item.speaker_level);
                        }
                    }


                    if($("#speaker_level_name").length>0)
                    {
                        if(ui.item.hasOwnProperty('speaker_level_name'))
                        {
                            $('#speaker_level_name').html(ui.item.speaker_level_name);
                        }
                    }

                }
            }

            if(!isNullOrEmpty(callBack))
            {

                if(callBackParam == null)
                {
                    callBack();
                }
                else
                {
                    callBack(callBackParam);
                }
            }
        }
    })
}

//ajax模糊查询课件
function ajaxGetFuzzyForFile(url, ctrlIdName, ctrlIdValue,param, callBack, callBackParam)
{
    var ctrl = "#" + ctrlIdName;
    $(ctrl).autocomplete({
        source: url + '/ajaxGetFuzzyForFile.html',
        select: function( event, ui ) {
            if(!isNullOrEmpty(ctrlIdValue))
            {
                console.log(ui.item);
                if(ui.item)
                {
                    $("#" + ctrlIdName).val(ui.item.file_no);
                    $("#" + ctrlIdValue).val(ui.item.id);
                    $('#doctor_file_name').html(ui.item.name);
                    $('#file_name').html(ui.item.name);
                    $('#file_intro').html(ui.item.intro);

                    // //课件搜索
                    // if($("#doctor_file_name").length>0)
                    // {
                    //     if(ui.item.hasOwnProperty('file_name'))
                    //     {
                    //
                    //     }
                    // }
                }
            }

            if(!isNullOrEmpty(callBack))
            {

                if(callBackParam == null)
                {
                    callBack();
                }
                else
                {
                    callBack(callBackParam);
                }
            }
        }
    })
}



//ajax获取周
function ajaxGetWeekOption(url,ctrlId,year,defaultValue)
{

    $.ajax({
        type:"POST",
        url:url + "/ajaxGetWeek",
        data:{year:year
            ,defaultValue:defaultValue
        },
        success:function(data){
            $("#" + ctrlId).html(data);
            $("#" + ctrlId).change();
        },
    });
}

//获取每周的周一周日
function ajaxGetWeekDay(url,year,week,ctrlId1,ctrlId2)
{
    $.ajax({
        type:"POST",
        url:url + "/ajaxGetWeekDay",
        data:{year:year
            ,week:week
        },
        success:function(data){
            $("#" + ctrlId1).val(data.begin_date);
            $("#" + ctrlId2).val(data.end_date);
        },
    });

}


/**
 * 时间计算
 * @param date date yyyy-mm-dd 时间格式
 * @param count int 变化的数值
 * @param type  int 1:年 2:月 3:日
 * @returns {*}
 */
function dateAdd(date,count,type)
{
    var date_arr = date.split("-");
    if(date_arr.length != 3)
    {
        return '';
    }
    count = parseInt(count);
    type = parseInt(type);

    var d=new Date();
    d.setFullYear(date_arr[0],parseInt(date_arr[1]) - 1,date_arr[2]);

    switch(type)
    {
        case 1:
            d.setFullYear(parseInt(d.getFullYear())+count);
            break;
        case 2:
            d.setMonth(parseInt(d.getMonth())+count);
            break;
        case 3:
            d.setDate(parseInt(d.getDate())+count);
            break;
    }

    var month=parseInt(d.getMonth())+1;
    var day = parseInt(d.getDate());
    if(month<10){
        month = "0"+month;
    }
    if(day<10){
        day = "0"+day;
    }
    var val = d.getFullYear()+"-"+month+"-"+day;
    return val;

}


//ajax根据地区药品联动销售
function ajaxGetSalesOption(url,ctrlId,small_code,medicine_type,firstOption,default_value,callBack,callBackParam)
{

    if(small_code == '' || medicine_type == '')
    {
        var data = "<option value=''>" + firstOption + "</option>";
        $("#" + ctrlId).html(data);

        if(!isNullOrEmpty(callBack))
        {
            if(callBackParam == null)
            {
                callBack();
            }
            else
            {
                callBack(callBackParam);
            }
        }
    }
    else
    {

        $.ajax({
            type:"POST",
            url:url + "/ajaxGetSales",
            data:{small_code:small_code
                ,medicine_type:medicine_type
                ,firstOption:firstOption
                ,default_value:default_value
            },
            success:function(data){
                $("#" + ctrlId).html(data);

                if(!isNullOrEmpty(callBack))
                {
                    if(callBackParam == null)
                    {
                        callBack();
                    }
                    else
                    {
                        callBack(callBackParam);
                    }
                }
            }
        });
    }
}

/**
 * ajax获取商业在所选季度对应地区下的销售数据
 * @param url
 * @param place_id
 * @param year
 * @param season
 * @param medicine_type
 * @param ctrlId
 * @param firstOption
 * @param default_value
 */
function ajaxGetPlaceUserOption(url, place_id, year, season, medicine_type, ctrlId, firstOption, default_value)
{
    if(place_id == '' || year == '' || season == '' || medicine_type == '')
    {
        if($.isArray(ctrlId))
        {
            for(var i=0;i<ctrlId.length;i++)
            {
                $("#" + ctrlId[i]).html("<option value=''>" + firstOption + "</option>");
            }
        }
        else
        {
            $("#" + ctrlId).html("<option value=''>" + firstOption + "</option>");
        }
    }
    else
    {
        $.ajax({
            type:"POST",
            url:url + "/ajaxGetPlaceUser.html",
            async: false,
            data:{
                place_id:place_id
                ,year:year
                ,season:season
                ,medicine_type:medicine_type
                ,firstOption:firstOption
                ,default_value:default_value
            },
            success:function(data){
                if($.isArray(ctrlId))
                {
                    for(var i=0;i<ctrlId.length;i++)
                    {
                        $("#" + ctrlId[i]).html(data);
                    }
                }
                else
                {
                    $("#" + ctrlId).html(data);
                }

            }
        });
    }
}

/**
 * ajax获取年和地点下的人员
 * @param url
 * @param ctrlId
 * @param firstOption
 * @param year
 * @param small_code
 * @param medicine_type
 * @param default_value
 */
function ajaxGetHistoryUser4YearOption(url,ctrlId,year,small_code,medicine_type,firstOption,default_value)
{
    if (year == '' || small_code == '' || medicine_type == '') {
        if ($.isArray(ctrlId)) {
            for (var i = 0; i < ctrlId.length; i++) {
                $("#" + ctrlId[i]).html("<option value=''>" + firstOption + "</option>");
            }
        }
        else {
            $("#" + ctrlId).html("<option value=''>" + firstOption + "</option>");
        }
    }
    else {
        $.ajax({
            type: "POST",
            url: url + "/ajaxGetHistoryUser4Year.html",
            async: false,
            data: {
                year: year
                , small_code: small_code
                , medicine_type: medicine_type
                , firstOption: firstOption
                , default_value: default_value
            },
            success: function (data) {
                if ($.isArray(ctrlId)) {
                    for (var i = 0; i < ctrlId.length; i++) {
                        $("#" + ctrlId[i]).html(data);
                    }
                }
                else {
                    $("#" + ctrlId).html(data);
                }

            }
        });
    }
}

/**
 * ajax获取季和地点下的人员
 * @param url
 * @param ctrlId
 * @param firstOption
 * @param year
 * @param season
 * @param small_code
 * @param medicine_type
 * @param default_value
 */
function ajaxGetHistoryUser4YearSeasonOption(url,ctrlId,year,season,small_code,medicine_type,firstOption,default_value)
{
    if (year == '' || season == '' || small_code == '' || medicine_type == '') {
        if ($.isArray(ctrlId)) {
            for (var i = 0; i < ctrlId.length; i++) {
                $("#" + ctrlId[i]).html("<option value=''>" + firstOption + "</option>");
            }
        }
        else {
            $("#" + ctrlId).html("<option value=''>" + firstOption + "</option>");
        }
    }
    else {
        $.ajax({
            type: "POST",
            url: url + "/ajaxGetHistoryUser4YearSeason.html",
            async: false,
            data: {
                year: year
                , season: season
                , small_code: small_code
                , medicine_type: medicine_type
                , firstOption: firstOption
                , default_value: default_value
            },
            success: function (data) {
                if ($.isArray(ctrlId)) {
                    for (var i = 0; i < ctrlId.length; i++) {
                        $("#" + ctrlId[i]).html(data);
                    }
                }
                else {
                    $("#" + ctrlId).html(data);
                }

            }
        });
    }
}

//ajax根据职位和药品联动地区
function getSmallAreaForPositionOption(url,ctrlId,user_id,medicine_type,position_arr,firstOption,default_value,callBack,callBackParam)
{

    if(user_id == '' || medicine_type == '')
    {
        var data = "<option value=''>" + firstOption + "</option>";
        $("#" + ctrlId).html(data);

        if(!isNullOrEmpty(callBack))
        {
            if(callBackParam == null)
            {
                callBack();
            }
            else
            {
                callBack(callBackParam);
            }
        }
    }
    else
    {

        $.ajax({
            type:"POST",
            url:url + "/ajaxGetSmallAreaForPosition",
            data:
            {
                user_id: user_id
                ,position_arr:position_arr
                ,medicine_type:medicine_type
                ,firstOption:firstOption
                ,default_value:default_value
            },
            success:function(data){
                $("#" + ctrlId).html(data);

                if(!isNullOrEmpty(callBack))
                {
                    if(callBackParam == null)
                    {
                        callBack();
                    }
                    else
                    {
                        callBack(callBackParam);
                    }
                }
            }
        });
    }
}