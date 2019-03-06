<?php
$this->addBreadcrumb('Проверка пользователей');
?>

<p>Проверка идет с недавно зарегистрированных к более старым<br>
    Проверка будет прервана, если сервис www.stopforumspam.com ответит ошибкой<br>
    Если сервис пометит пользователя как спамера, его можно будет найти в разделе спамеры<br>
    Пользователь удаляется из списка спамеров при удалении в разделе Пользователи<br>
    <b>Не закрывайте страницу, пока идет проверка</b>
</p>

<p>Всего пользователей: <?php echo $all; ?></p>
<div id="last" itemstatus="offline">
    <?php if ($last) { ?>
        <p>Последний проверенный пользователь c id <?php echo $lastuser['id']; ?>: <a target="_blank" href="<?php echo href_to('admin', 'users', array('edit', $lastuser['id'])); ?>"><?php echo $lastuser['nickname']; ?></a></p>
    <?php } ?>
</div>
<a class="ajaxlink check check_continue"<?php if(!$last){ ?> style="display: none;"<?php } ?>>продолжить проверку</a>
<a class="ajaxlink check check_new">начать проверку заново</a>
<a class="ajaxlink stop" style="display: none;">остановить проверку</a>





<style>
    .check,.stop{
        cursor: pointer;
        display: block;
        float: left;
        clear: both;
        margin-top: 10px;
    }
</style>
<script>
    
var check = (function($){
    this.status = false;//счетчик от повторных нажатий...
    this.start = function(param){
        if(this.status !== false){
            return false;
        }
        this.status = true;
        this.working();
        var _this = this;
        var url = "<?php echo $this->href_to('check'); ?>";
        if (param !== undefined){
            url = url + "/new";
        }
        $.getJSON(url, function (result) {
            console.log(result);
            if (result.success) {
                if($('#last').attr('itemstatus') === 'online'){
                    $('#last').html(result.text);
                    _this.status = false;
                    _this.start();
                }
            } else {
                $('#last').html(result.text);
                _this.ended();
            }
        });
    };
    this.init = function(){
        this.event();
    };
    this.event = function(){
        var _this = this;
        $('.check_continue').click(function () {
            $('#last').attr('itemstatus','online');
            _this.start();
        });
        $('.check_new').click(function () {
            $('#last').attr('itemstatus','online');
            _this.start('new');
        });
        $('.stop').click(function () {
            $('#last').attr('itemstatus','offline');
            _this.notworking();
        });
    };
    this.working = function(){
        $('.check').hide();
        $('.stop').show();
    };
    this.notworking = function(){
        this.status = false;
        $('.check').show();
        $('.stop').hide();
    };
    this.ended = function(){
        this.status = false;
        $('.check_new').show();
        $('.stop').hide();
    };
    return this;
}).call(check || {},jQuery);
    
(function () {
    check.init();
})();
</script>