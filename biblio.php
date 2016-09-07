<div class="col-sm-12 text-right">
	<button type="button" class="btn btn-sm btn-success" onclick="$('#modalBiblio').modal('show')">
		<span class="glyphicon glyphicon-plus"></span>
		<b>Agregar Libro</b>
		<span class="glyphicon glyphicon-book"></span>
	</button>
</div>
<div class="col-sm-12">
	<table class="table table-hover">
		<thead>
			<tr>
				<th>Libro</th>
				<th>Area</th>
			</tr>
		</thead>
		<tbody>
			<tr ng-repeat="book in catBibliografia">
				<td>
					<a ng-href="{{book.url}}" target="_blank">{{book.bibliografia}}</a>
				</td>
				<td>{{book.area}}</td>
			</tr>
		</tbody>
	</table>
</div>

<!-- Modal -->
<div class="modal" id="modalBiblio" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content panel-primary">
      		<div class="modal-header panel-heading">
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        		<h4 class="modal-title" id="myModalLabel"><i class="glyphicon glyphicon-cloud-upload"></i> Subir Libro</h4>
      		</div>
      		<div class="modal-body">
				<form enctype="multipart/form-data">
					<label>Area</label>
                	<select name="idArea" id="idArea" ng-model="idArea" class="form-control">
                		<option value="{{ar.idArea}}" ng-repeat="ar in catArea">{{ar.area}}</option>
                	</select>
                	<label>Seleccione Libro</label>
                	<input id="documentos" name="archivos" type="file">
            	</form>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      		</div>
    	</div>
  	</div>
</div>




