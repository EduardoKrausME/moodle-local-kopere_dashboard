define(["jquery"], function($) {
    return {

        mark: function(key) {

            let toggleStateTable = false;
            $(".mark-all-table").click(function () {
                // Seleciona todos os checkboxes com a classe .table-checkbox-table e alterna o estado.
                $(".table-checkbox-table").prop("checked", toggleStateTable);

                // Inverte o estado para a próxima ação.
                toggleStateTable = !toggleStateTable;
            });

            let toggleStateData = false;
            $(".mark-all-data").click(function () {
                // Seleciona todos os checkboxes com a classe .tabl-checkbox-data e alterna o estado.
                $(".table-checkbox-data").prop("checked", toggleStateData);

                // Inverte o estado para a próxima ação.
                toggleStateData = !toggleStateData;
            });

            $(".mark-line-table").click(function() {
                // Encontra a linha (tr) mais próxima do botão clicado.
                let $row = $(this).closest("tr");

                // Seleciona todos os checkboxes dentro dessa linha.
                let $checkboxes = $row.find("input[type='checkbox']");

                // Verifica se pelo menos um checkbox está marcado.
                let allChecked = $checkboxes.length > 0 && $checkboxes.filter(":checked").length === $checkboxes.length;

                // Alterna o estado dos checkboxes.
                $checkboxes.prop("checked", !allChecked);
            })
        },
    };
});
