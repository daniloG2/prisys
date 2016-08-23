<div class="col-sm-12" style="margin-bottom:30px;">
	<div class="col-sm-8">
		<img ng-src="img/{{tema.idImportancia}}.png" height="20" alt="icon">
		<b>{{tema.tema}}</b>
		<kbd>A{{tema.idArea}}</kbd>
		<a ng-href="#/tag/{{tg}}" class="label label-primary" ng-repeat="tg in tema.etiquetas" style="margin-right:3px;display:inline-block">
			<span class="glyphicon glyphicon-tag"></span>
			{{tg}}
		</a>
	</div>
	<div class="col-sm-4 text-right">
		<button type="button" class="btn btn-xs btn-primary" ng-click="tema.showBib=!tema.showBib">
			<span class="glyphicon glyphicon-book"></span>
			<span class="badge">{{tema.bibliografias.length}}</span>
		</button>
		<span class="label label-info">{{tema.usuario}}</span>
		{{tema.fecha}}
	</div>
	<div class="col-sm-12" style="margin-top:5px;margin-bottom:5px">
		<div class="table-responsive" ng-show="tema.showBib">
			<table class="table table-hover">
				<tbody>
					<tr ng-repeat="bib in tema.bibliografias">
						<td>
							<a ng-href="book/{{bib.url}}" target="_blank" ng-show="bib.idTipoBibliografia==1">
								<span class="glyphicon glyphicon-book"></span>
								{{bib.bibliografia}} ({{bib.paginaLibro}})
							</a>
							<a ng-href="{{bib.url}}" target="_blank" ng-show="bib.idTipoBibliografia==2">
								<span class="glyphicon glyphicon-link"></span>
								{{bib.bibliografia}}
							</a>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<div class="col-sm-12" style="margin:0;padding:0;">
		<div id="verTema"></div>
	</div>

	<!-- COMENTARIO Y MODIFICAR -->
	<div class="col-sm-12 text-right" style="margin-top:-18px;margin-bottom:4px">
		<button type="button" class="btn btn-sm btn-primary" ng-click="modificarTema()">
			<span class="glyphicon glyphicon-pencil"></span>
			<b>Modificar</b>
		</button>
		<button type="button" class="btn btn-sm btn-default" ng-click="ventana1=true">
			<span class="glyphicon glyphicon-plus"></span>
			<b>Agregar</b>
			<span class="glyphicon glyphicon-comment"></span>
		</button>
	</div>
	
	<div class="comentarios col-sm-12" ng-repeat="com in tema.comentarios">
		<div class="por col-sm-4">
			<span class="label label-primary">{{com.nombre}}</span>
		</div>
		<div class="col-sm-4 text-center">
			<div class="votar" ng-click="com.showVotacion=!com.showVotacion">
				<span class="glyphicon glyphicon-question-sign" ng-if="com.miVoto == 0"></span>
				<img ng-src="img/{{com.miVoto}}.png" height="22px" style="margin-top:-7px;margin-left:-2px;" ng-if="com.miVoto > 0">

				<div class="lstImg" ng-show="com.showVotacion">
					<img src="img/1.png" ng-click="votar( com.idComentario, 1 )" height="25px">
					<img src="img/2.png" ng-click="votar( com.idComentario, 2 )" height="25px">
					<img src="img/3.png" ng-click="votar( com.idComentario, 3 )" height="25px">
					<img src="img/4.png" ng-click="votar( com.idComentario, 4 )" height="25px">
					<img src="img/5.png" ng-click="votar( com.idComentario, 5 )" height="25px">
				</div>
			</div>
			<div style="display:inline-block;height:30px;vertical-align:middle">
				<span class="glyphicon glyphicon-chevron-right"></span>
				<span class="glyphicon glyphicon-question-sign" ng-if="com.ranking == 0"></span>
				<img ng-src="img/{{com.ranking}}.png" height="25px" ng-if="com.ranking > 0">
			</div>
		</div>
		<div class="fecha col-sm-4 text-right">{{com.fecha}}</div>
		<div class="comentario col-sm-12" ng-bind-html="Html( com.comentario )"></div>
	</div>
</div>

<div class="ventana" ng-show="ventana1" ng-init="ventana1=false">
	<div class="contenedor col-sm-10 col-sm-offset-1">
		<div class="cerrar" ng-click="ventana1=false">
			<span class="glyphicon glyphicon-remove"></span>
		</div>
		<div class="titulo">Agregar Comentario</div>
		<div class="contenido col-sm-12">
			<div class="col-sm-12">
				<div id="divEdit"></div>
			</div>
			<div class="col-sm-12 text-right" style="margin-top:7px;">
				<button type="button" class="btn btn-success" ng-click="agregarComentario()">
					<span class="glyphicon glyphicon-ok"></span>
					<b>Guardar Comentario</b>
				</button>
			</div>
		</div>
	</div>
</div>
