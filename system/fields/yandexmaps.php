<?php

class fieldYandexmaps extends cmsFormField {

    public $title = LANG_FIELD_YANDEXMAPS;
    public $sql = 'varchar(255) NULL DEFAULT NULL';
    public $filter_type = 'str';

    public function getFilterInput($value) {
        return false;
    }

    public function getOptions() {
        return array(
            new fieldString('center', array(
                'title' => LANG_FIELD_YANDEXMAPS_CENTER,
                'default' => '57.7801162,55.7354444'
                    )),
            new fieldString('zoom', array(
                'title' => LANG_FIELD_YANDEXMAPS_ZOOM,
                'default' => '13',
                'hint' => LANG_FIELD_YANDEXMAPS_ZOOM_HINT
                    )),
            new fieldString('zoom2', array(
                'title' => LANG_FIELD_YANDEXMAPS_ZOOM2,
                'default' => '15',
                'hint' => LANG_FIELD_YANDEXMAPS_ZOOM2_HINT
                    )),
            new fieldCheckbox('hide', array(
                'title' => LANG_FIELD_YANDEXMAPS_HIDE
                    )),
            new fieldString('api', array(
                'title' => LANG_FIELD_YANDEXMAPS_API,
                'hint' => LANG_FIELD_YANDEXMAPS_API_HINT
                    ))
        );
    }

    public function parse($value) {

        if (!$value)
            return false;
        if (empty($this->item))
            return false;

        $cn = $value;
        $zoom = $this->getOption('zoom2');
        $hide = $this->getOption('hide');
        $id = (!empty($this->item['id'])) ? $this->item['id'] : "";
        $api =(!empty($this->getOption('api'))) ? $this->getOption('api') : false;
        $url = 'https://api-maps.yandex.ru/2.1/?lang=ru_RU';
        if($api){
            $url .= "&apikey=" . $api;
        }

        cmsTemplate::getInstance()->addJS($url, '', false);

        ob_start();
        ?>

        <script type="text/javascript">
            ymaps.ready(init);
            var myMap,
                    myPlacemark;

            function init() {
                myMap = new ymaps.Map("map<?php html($id); ?>", {
                    center: [<?php html($cn); ?>],
                    zoom: <?php html($zoom); ?>
                }, {suppressMapOpenBlock: true});

        <?php if ($hide) { ?>
            $('.showmap').click(function(e){
            e.preventDefault();
        <?php } ?>
                myMap.container.fitToViewport();
                myPlacemark = new ymaps.Placemark([<?php html($cn); ?>], {});
                myMap.geoObjects.add(myPlacemark);
                
        <?php if ($hide) { ?>
            });
        <?php } ?>

            }

        </script>
        <?php if ($hide) { ?>
            <a href="#" class="showmap">
               <?php echo LANG_FIELD_YANDEXMAPS_HIDE_TEXT; ?>
            </a>
        <?php } ?>
        <div id="map<?php html($id); ?>" class="mapdiv" <?php if ($hide) { ?>style="display: none;"<?php } ?>></div>
        <div class="maphover" <?php if ($hide) { ?>style="display: none;"<?php } ?>></div>
        <script>
            $(".maphover").on({
                click: function () {
                    $(this).hide('fast');
                }, touch: function () {
                    $(this).hide('fast');
                }
            });
            $('.showmap').click(function(e){
                e.preventDefault();
                $('#map<?php html($id); ?>').removeAttr('style').next('.maphover').removeAttr('style');
            });
        </script>
        <style>
            .mapdiv, .maphover{box-sizing: border-box;}
            .mapdiv{width: 100%; height: 450px; padding: 5px;background: #ECF0F1;}
            .maphover{width: 100%; height: 450px; margin-top: -450px; position: absolute; z-index: 9999;}
            .showmap{width: 45%;padding: 10px; background: #ECF0F1;font-weight: bold;text-decoration: none;display: inline-block;}
        </style>

        <?php
        $map = ob_get_clean();


        return $map;
    }

}
