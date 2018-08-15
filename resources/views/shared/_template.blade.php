<a id="ok" class="col-xs-12">
    <div><strong><a href="#">ID:{{ $t['template_id'] }}    {{ $t['user_desc'] }}</a></strong>
        <button onclick="codecommit({{ $t['template_id'] }})">替换当前模板</button>
    </div>
    <div>author {{ $t['developer'] }}</div>
    <div>in {{ $t['source_miniprogram'] }}（{{ $t['source_miniprogram_appid'] }}）</div>

</a>
<script>

    function codecommit(templateid) {
        var appid = getQueryString('appid');
        axios({
            method: "post",
            url: "/api/wechat/codecommit",
            data: {
                templateid: templateid,
                extjson: '{"extEnable": true,"extAppid":"'+appid+'"}',
                version: '1',
                appid: appid,
                desc: '.'
            }
        }).then(function (response) {
            console.log(response);
            const data = response.data;
            alert(data.errmsg);
            if (data.errorcode == 0) {

            } else {
            }


        })
            .catch(function (err) {
                console.log(err);
            });
    }

    function getQueryString(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return unescape(r[2]);
        return null;
    }
</script>