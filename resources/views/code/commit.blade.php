@extends('layouts/app')
@section('title','提交审核')
@section("content")
    <div class="row">
        <?php
        $pages = array_key_exists('page_list', $datas[1]) ? $datas[1]['page_list'] : [];
        $category_list = array_key_exists('category_list', $datas[0]) ? $datas[0]['category_list'] : [];//$datas[0]['category_list'];
        $status = $datas[2]; ;
        ?>

        <p>
            @if(!array_key_exists('status',$status) ||$status['status']!=2 )  {{-- 未曾提交审核或者未在审核中的小程序  可以显示提交表单--}}
            请选择分类

            <select name="first_class" id="first_class" title="一级分类">
                @foreach($category_list as $category)
                    @if(array_key_exists('first_id',$category))
                        <option id="{{ $category['first_id'] }}">{{ $category['first_class'] }}


                        </option>
                    @endif
                @endforeach
            </select>
            <select id="second_class" title="二级分类">
                @foreach($category_list as $category)
                    @if(array_key_exists('second_id',$category))
                        <option id="{{ $category['second_id']}}"> {{ $category['second_class'] }}</option>
                    @endif
                @endforeach
            </select>
            <select id="third_class" title="三级分类">
                @foreach($category_list as $category)
                    @if(array_key_exists('third_id',$category))
                        <option id="{{ $category['third_id']}}"> {{ $category['third_class'] }}</option>
                    @endif
                @endforeach
            </select>
            <br>
            请选择入口页面
            <select name="address" id="address" title="入口页面">
                @if($pages !=null)
                    <@foreach($pages as $page)
                        <option> {{ $page }}</option>
                    @endforeach
                @endif
            </select> <br>
            <br>
            标签：<input id="tag" placeholder="请输入您的备注">
            入口页面标题：<input id="title" placeholder="">
            <button onclick="commit()">提交审核</button>
        @endif
        <p>
            @if(array_key_exists('status',$status))
                @if($status!=''&& $status !=0 )

                    审核状态 : @if($status['status']==0)
                        审核成功，现在可以  <br> <a onclick="toRelease()"> 一键发布！</a>
                    @elseif($status['status']==1)
                        审核失败 <br/>
                        原因 ：<?php echo $status['reason']?>
                    @elseif($status['status']==2)
                        审核中
                    @else
                        未在审核
                    @endif

                    审核ID : {{ $status['auditid']}}

        @endif
        @else
            {{--此小程序注册信息不完整，请先到微信公众平台完善小程序信息--}}
        @endif
    </div>

@endsection
<script>
    function commit() {
        const first_id = $(":selected", "#first_class").attr("id");
        const second_id = $(":selected", "#second_class").attr("id");
        const third_id = $(":selected", "#third_class").attr("id");
        const first_class = document.getElementById('first_class').value;
        const second_class = document.getElementById('second_class').value;
        const third_class = document.getElementById('third_class').value;
        const address = document.getElementById('address').value;
        const tag = document.getElementById('tag').value;
        const title = document.getElementById('title').value;
        const appid = getQueryString('appid');
        axios({
            method: "post",
            url: "/api/wechat/submitaudit",
            data: {
                appid: appid,
                first_id: first_id,
                second_id: second_id,
                third_id: third_id,
                first_class: first_class,
                second_class: second_class,
                third_class: third_class,
                address: address,
                tag: tag,
                title: title
            }
        }).then(function (response) {
            console.log(response);
            const data = response.data;
            alert(data.errmsg);
            if (data.errorcode == 0) {
                window.location.reload();
            } else {
            }


        })
            .catch(function (err) {
                console.log(err);
            });
    }

    function toRelease() {
        const appid = getQueryString('appid');
        window.location = '/wechat/release?appid=' + appid;
    }

    function getQueryString(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return unescape(r[2]);
        return null;
    }
</script>