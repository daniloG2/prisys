<div class="col-sm-12" style="margin-top:-17px;margin-bottom:7px">
	<div class="col-sm-4">
		<h4 ng-show="tipoTema=='tema'">
			<span class="label label-success">Ingresar Nuevo Tema</span>
		</h4>
		<h4 ng-show="tipoTema=='pregunta'">
			<span class="label label-info">Ingresar Nuevo Pregunta</span>
		</h4>
		<h4 ng-show="tipoTema=='tips'">
			<span class="label label-primary">Ingresar Nuevo Tip</span>
		</h4>
	</div>
	<div class="col-sm-8 text-right">
		<a ng-href="#/ingresar/tema" class="btn btn-sm" ng-class="{'btn-primary':tipoTema=='tema', 'btn-link':tipoTema!='tema'}">
			<span class="glyphicon" 
				ng-class="{'glyphicon-ok-circle':tipoTema=='tema', 'glyphicon-remove-circle':tipoTema!='tema'}"></span>
			<b>Tema</b>
		</a>
		<a ng-href="#/ingresar/pregunta" class="btn btn-sm" ng-class="{'btn-primary':tipoTema=='pregunta', 'btn-link':tipoTema!='pregunta'}">
			<span class="glyphicon" 
				ng-class="{'glyphicon-ok-circle':tipoTema=='pregunta', 'glyphicon-remove-circle':tipoTema!='pregunta'}"></span>
			<b>Pregunta</b>
		</a>
		<a ng-href="#/ingresar/tips" class="btn btn-sm" ng-class="{'btn-primary':tipoTema=='tips', 'btn-link':tipoTema!='tips'}">
			<span class="glyphicon" 
				ng-class="{'glyphicon-ok-circle':tipoTema=='tips', 'glyphicon-remove-circle':tipoTema!='tips'}"></span>
			<b>Tip</b>
		</a>
	</div>
</div>

<div class="col-sm-12">
	<form method="POST" class="form-horizontal" name="formIngreso">
		<div class="form-group">
			<label class="col-sm-1">Título</label>
			<div class="col-sm-6">
				<input type="text" ng-model="tema.tema" class="form-control" placeholder="Tema" id="tituloTema" autofocus>
			</div>
			<label class="col-sm-1">Area</label>
			<div class="col-sm-4">
				<select ng-model="tema.idArea" class="form-control">
					<option value="{{a.idArea}}" ng-repeat="a in catArea">{{a.area}}</option>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-1">Importancia</label>
			<div class="col-sm-2">
				<select ng-model="tema.idImportancia" class="form-control">
					<option value="{{i.idImportancia}}" ng-repeat="i in catImportancia">{{i.importancia}}</option>
				</select>
			</div>
			<label class="col-sm-1">Tags</label>
			<div class="col-sm-2">
				<input type="text" ng-model="tag" class="form-control" placeholder="TAG" ng-keypress="$event.keyCode == 13 && addTag()">
			</div>
			<div class="col-sm-6">
				<kbd ng-repeat="tg in tema.tags" style="margin-left:3px;cursor:pointer" ng-click="tema.tags.splice($index, 1)">{{tg}}</kbd>
			</div>
		</div>

		<!-- CONTENIDO -->
		<div class="form-group" style="margin-top:-15px;">
			<div class="col-sm-12">
				<div id="divEdit"></div>
			</div>
		</div>
		<div class="col-sm-12 text-left" style="margin-top:-22px;margin-bottom:7px;">
			<button type="button" class="btn btn-sm" ng-class="{'btn-info': subirArchivo, ' btn-warning': !subirArchivo}" ng-click="subirArchivo=!subirArchivo">
				<span class="glyphicon" ng-class="{'glyphicon-check': subirArchivo, 'glyphicon-unchecked': !subirArchivo}"></span>
				Subir Archivos
			</button>
		</div>
		<div class="form-group" ng-show="tipoTema=='tema'">
			<label class="col-sm-2">Bibliografía</label>
			<div class="col-sm-3">
				<select ng-model="idTipoBibliografia" class="form-control">
					<option value="">Ningúno</option>
					<option value="{{b.idTipoBibliografia}}" ng-repeat="b in catTipoBibliografia" ng-show="$index<2">{{b.tipoBibliografia}}</option>
				</select>
			</div>
			<div class="col-sm-4">
				<select ng-model="idBibliografia" class="form-control" ng-show="idTipoBibliografia==1">
					<option value="{{b.idBibliografia}}" ng-repeat="b in catBibliografia">{{b.bibliografia}}</option>
				</select>
				<input type="text" ng-model="enlace" class="form-control" ng-show="idTipoBibliografia==2" placeholder="Dirección enlace">
			</div>
			<div class="col-sm-2">
				<input type="number" ng-model="paginaLibro" class="form-control" placeholder="# Página" ng-show="idTipoBibliografia==1">
			</div>
			<div class="col-sm-1 text-right">
				<button type="button" class="btn btn-sm btn-primary" ng-click="addBiblio()">
					<span class="glyphicon glyphicon-plus"></span>
				</button>
			</div>
			<div class="col-sm-11 col-sm-offset-1">
				<table class="table table-striped">
					<tbody>
						<tr ng-repeat="bi in tema.bibliografias">
							<td>{{bi.cont}}</td>
							<td>
								<span class="label label-info">
									{{bi.tipo}}
								</span>
							</td>
							<td>
								<button type="button" class="btn btn-sm btn-danger" ng-click="tema.bibliografias.splice($index, 1)">
									<span class="glyphicon glyphicon-remove"></span>
								</button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-12 text-center">
				<button type="button" class="btn btn-success" ng-click="guardarTema()">
					<span class="glyphicon glyphicon-ok"></span>
					<b>Guardar Tema</b>
				</button>
			</div>
		</div>
	</form>
</div>