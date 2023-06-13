<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Municípios</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('./css/app.css')}}">

</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 d-flex justify-content-center">
                <h1>Lista de Municípios do Rio Grande do Sul</h1>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 d-flex justify-content-center">
                <ul id="city-list" class="align-self-center"></ul>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 d-flex justify-content-around" id="pagination-links">
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="city-modal" tabindex="-1" role="dialog" aria-labelledby="city-modal-label">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="city-modal-label">Detalhes da Cidade</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
            <div id="loader" style="display: none;">
                <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
                </div>
            </div>
            <div id="city-details"></div>
            </div>
        </div>
        </div>
    </div>
  
    


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    
    <script>
        $(document).ready(function() {
            $(document).on("click", "#city-list li", function() {
                var cityId = $(this).data("id");

                // Exibir o loader
                $("#loader").show();

                // Fazer uma requisição AJAX para obter os detalhes da cidade
                $.ajax({
                    url: "{{ route('api.cities.details', ['id' => ':id']) }}".replace(':id', cityId),
                    method: "GET",
                    success: function(response) {
                        // Atualizar o conteúdo do modal com os detalhes da cidade
                        $("#city-details").html(
                        '<p>Microrregião: ' + response['microrregiao-nome'] + '</p>' +
                        '<p>Mesorregiao: ' + response['mesorregiao-nome'] + '</p>' +
                        '<p>Região Imediata: ' + response['regiao-imediata-nome'] + '</p>' +
                        '<p>Região Intermediária: ' + response['regiao-intermediaria-nome'] + '</p>' +
                        '<p>Região: ' + response['regiao-nome'] + '</p>'
                        );

                        $("#city-modal-label").html('Detalhes de ' + response['municipio-nome']);

                        // Esconder o loader
                        $("#loader").hide();

                        // Abrir o modal
                        $("#city-modal").modal("show");
                    },
                    error: function() {
                        // Esconder o loader em caso de erro
                        $("#loader").hide();

                        alert("Ocorreu um erro ao obter os detalhes da cidade.");
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            loadCities();
    
            function loadCities(page = 1) {
                $.ajax({
                    url: '{{ route("api.cities.cities") }}',
                    type: 'GET',
                    data: { page: page },
                    success: function(response) {
                        displayCities(response.data);
                        displayPagination(response);
                    },
                    error: function() {
                        alert('Não foi possível carregar os municípios.');
                    }
                });
            }
    
            function displayCities(cities) {
                var cityList = $('#city-list');
                cityList.empty();

                $.each(cities, function(index, city) {
                    var listItem = $('<li>').text(city.nome).attr('class', 'city').attr('data-id', city.codigo_ibge);
                    cityList.append(listItem);
                });
            }
    
            function displayPagination(pagination) {
                var paginationLinks = $('#pagination-links');
                paginationLinks.empty();
    
                if (pagination.last_page > 1) {
                    for (var i = 1; i <= pagination.last_page; i++) {
                        var link = $('<a>', {
                            href: '#',
                            text: i,
                            class: pagination.current_page === i ? 'active' : '',
                            click: function() {
                                var page = $(this).text();
                                loadCities(page);
                            }
                        });
    
                        paginationLinks.append(link);
                    }
                }
            }
        });
    </script>
    
</body>
</html>