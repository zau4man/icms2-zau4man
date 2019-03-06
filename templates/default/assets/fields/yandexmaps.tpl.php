<?php if ($field->title) { ?><label for="<?php echo $field->id; ?>"><?php echo $field->title; ?></label><?php } ?>

<?php
$api = (!empty($field->getOption('api'))) ? $field->getOption('api') : false;
$url = 'https://api-maps.yandex.ru/2.1/?lang=ru_RU';
if ($api) {
    $url .= "&apikey=" . $api;
}
$this->insertJS($url);
?>
<script type="text/javascript">
    ymaps.ready(init);
    var myMap,
            myPlacemark;

<?php
$cn = empty($value) ? $field->getOption('center') : $value;
$zoom = $field->getOption('zoom');
?>

    function init() {
        myMap = new ymaps.Map("mapayandexaunicid", {
            center: [<?php echo $cn; ?>],
            zoom: <?php echo $zoom; ?>,
            controls: ['zoomControl', 'typeSelector']
        }, {suppressMapOpenBlock: true});

        var searchControl = new ymaps.control.SearchControl({
            options: {
                float: 'left',
                floatIndex: 100,
                noPlacemark: true
            }
        });

        myMap.controls.add(searchControl);

<?php if ($value) { ?>

            myPlacemark = new ymaps.Placemark([<?php echo $cn; ?>], {});
            myMap.geoObjects.add(myPlacemark);

<?php } ?>

        myMap.events.add('click', function (e) {
            myMap.geoObjects.remove(myPlacemark);
            var coords = e.get('coords');
            myPlacemark = new ymaps.Placemark([coords[0], coords[1]], {});
            myMap.geoObjects.add(myPlacemark);
            $('#<?php echo $field->name; ?>').val(coords[0] + ',' + coords[1]);
        });
    }

    $(document).ready(function () {
        $('.clearmap').click(function (event) {
            event.preventDefault();
            $('#<?php echo $field->name; ?>').val('');
            myMap.geoObjects.remove(myPlacemark);
        });
    });

</script>

<div id="mapayandexaunicid" style="width: 100%; height: 350px"></div>    

<?php echo html_input('hidden', $field->element_name, $value, array('id' => $field->id)); ?>
<a class="clearmap" href="#"><?php echo LANG_FIELD_YANDEXMAPS_CLEAR; ?></a>