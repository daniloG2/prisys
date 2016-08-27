<div class="col-sm-12">
	<form method="POST" class="form-horizontal" name="formIngreso">
		<div class="form-group">
			<label class="col-sm-1">Tema</label>
			<div class="col-sm-6">
				<input type="text" ng-model="tema.tema" class="form-control" placeholder="Tema" autofocus>
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
		<div class="form-group">
			<label class="col-sm-1">Bibliografía</label>
			<div class="col-sm-2">
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
			<div class="col-sm-2">
				<button type="button" class="btn btn-primary" ng-click="addBiblio()">
					<span class="glyphicon glyphicon-ok"></span>
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
			<div class="col-sm-10 col-sm-offset-2">
				<button type="button" class="btn btn-success" ng-click="guardarTema()">
					<span class="glyphicon glyphicon-ok"></span>
					<b>Guardar Tema</b>
				</button>
			</div>
		</div>
	</form>
</div>