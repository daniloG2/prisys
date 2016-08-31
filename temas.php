<div class="col-sm-4">
	<input type="text" ng-model="filtro" id="filtro" ng-init="filtro=''" class="form-control" placeholder="Filtrar" autofocus>
</div>
<div class="col-sm-8 text-right">
	<a ng-href="#/temas/{{ar.idArea}}" class="btn" ng-repeat="ar in $parent.catArea"
		ng-class="{'btn-link':ar.idArea!=idArea, 'btn-primary':ar.idArea==idArea}">{{ar.area}}</a>	
</div>
<div class="col-sm-12">
	<table class="table table-hover">
		<thead>
			<tr>
				<th>Tema</th>
				<th>Descripción</th>
				<th>Fecha</th>
				<th style="width:160px">Tags</th>
			</tr>
		</thead>
		<tbody>
			<tr ng-repeat="tm in lstTemas | filter:filtro">
				<td>
					<img ng-src="img/{{tm.idImportancia}}.png" height="20" alt="icon">
					<a ng-href="#/tema/{{tm.idTema}}">
						{{tm.tema}}
					</a>
					<a ng-href="#/tema/{{tm.idTema}}" target="_blank">
						<span class="glyphicon glyphicon-new-window" style="font-size:14px;margin-left:5px"></span>
					</a>
					<span class="label label-success" style="margin-left:7px" ng-show="tm.visto">Visto</span>
					<span class="label label-default1" style="margin-left:7px" ng-show="!tm.visto">No Visto</span>
				</td>
				<td>{{tm.descripcionCorta}}</td>
				<td>{{tm.fecha}}</td>
				<td>
					<a ng-href="#/tag/{{tg}}" class="label label-primary" ng-repeat="tg in tm.etiquetas" style="margin-right:3px;display:inline-block">
						<span class="glyphicon glyphicon-tag"></span>
						{{tg}}
					</a>
				</td>
			</tr>
		</tbody>
	</table>
</div>