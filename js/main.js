file_inp.addEventListener("change", () => {
    let file = file_inp.files[0];

    let fr = new FileReader();

    fr.addEventListener(
        "load",
        function () {
            let res = fr.result.replace("data:text/xml;base64,", "");
            xmlDoc = base64toXML(res);

            // Парсер не может корректно обработать некоторые моменты и чтобы они не мешали, я удаляю оповещения об этом
            $(xmlDoc).find("parsererror").remove();

            let has_succes_item = false;

            // Поиск подходящих элементов
            $(xmlDoc)
                .find("component")
                .map(function () {
                    if (
                        $(this).attr("id") != "030-032-000-000" ||
                        !$(this).has("limit").has("error").has("value")[0] ||
                        $(this).children().length != 3 ||
                        $(this)[0].attributes.length != 1 ||
                        $(this).find("limit").html() != "" ||
                        $(this).find("value").html() != "" ||
                        $(this).find("error").html() != "error"
                    )
                        return false;

                    has_succes_item = true;
                });

            if (!has_succes_item) {
                create_message("Не найдено соответствий");
                file_inp.value = "";
            } else {
                save(file);
            }
        },
        false
    );

    fr.readAsDataURL(file);
});

function base64toXML(base64) {
    let parser = new DOMParser();

    // Так как в задании сказано, что компоненты геристро независимые, я перевожу всё в нижний регистр
    let xmlString = atob(base64).toLowerCase();

    return parser.parseFromString(xmlString, "text/xml");
}

function save(file) {
    let formData = new FormData();
    formData.append("xml", file, file.name);
    $.ajax({
        type: "POST",
        url: "/api/save",
        cache: false,
        contentType: false,
        processData: false,
        data: formData,
        dataType: "json",
        success: ({ uniq_name }) => {
            // Отображаю модальное окно с кодом xml файла
            $(".modal").removeClass("--none");
            // $('.code').load(file)

            $.ajax({
                type: "POST",
                url: "/api/files",
                success: (data) => {
                    create_table(data);
                },
            });
        },
        error: (data) => {
            console.log(data);
        },
    });
}

function create_message(text, err = true) {
    $(".message-box").append(
        '<div class="message ' +
            (err ? "" : "--not-err") +
            '">' +
            text +
            "</div>"
    );
    let item = $(".message-box").children().last();
    setTimeout(() => {
        item.animate({ opacity: 0 }, 2000, function () {
            $(this).remove();
        });
    }, 5000);
}

function create_table(data) {
    data.map((item) => {
        $(".table").append(
            '<div class="tr"><div class="td id">' +
                item.id +
                '</div><div class="td uniq">' +
                item.uniq_name +
                '</div><div class="td name">' +
                item.file_name +
                '</div><div class="td date">' +
                item.date +
                "</div></div>"
        );
    });
}

search_inp.addEventListener('keyup', ()=>{
    let filter = search_inp.value
        
    $('.tr').map(function(){
        let is_succes = false
        $(this).find('>*').map(function(){
            if($(this).html().toLowerCase().indexOf(filter) > -1) is_succes = true
        })
        if(is_succes) $(this).css({display: ''})
            else $(this).css({display: 'none'})
    })
})

let ascending = false

$('.th>*').click(function(){

    $.ajax({
        url: "/api/sort",
        method: "POST",
        data: {
            filter: $(this).attr('data-f'),
            ascending: (ascending? 1:0)
        },
        success: data => {
            $('.tr').remove()
            create_table(data)
            ascending = !ascending
        },
        error: data=>{
            console.log(data);
        }
    })
})

