# Instalação do sistema:

 - Rodar os seguintes comandos:
	 - composer install;
	 - npm install;
 - Copiar o arquivo .env.example e renomear para .env;
 - **É necessário ter o Redis instalado na máquina para o uso de cache;**

## Projeto em produção:
[Acesse aqui](https://idez.caprino.top/)

## Endpoints:

	

 -  [/api/citites](https://idez.caprino.top/api/cities)

	- Retorna uma lista de Municípios;
	- Parâmetros(Opcional):
			- "[page](https://idez.caprino.top/api/cities?page=10)" define qual página deve ser acessada.

 - /api/cities/details

	 - Retorna as informações completas de um município;
	 - Parâmetros(Obrigatório):
			 - [/api/cities/details/4312757](https://idez.caprino.top/api/cities/details/4312757)
