<!DOCTYPE html>
<html ng-app="insert_db" >
<head>
	<title>test</title>
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script>
		var ajaxExample = angular.module('insert_db', []);
		ajaxExample.controller('mainController',function($scope,$http){
		    $scope.form_data_list;	
		    $scope.preview_form_data_list;	
		    $scope.update_status;  
		    $scope.delete_status;
		    $scope.id; 
		    $scope.status_visibility=false;
		    $scope.submit_visibility=true;
		    $scope.info=true;
		    $scope.confirmation;
		    $scope.insert = function() {
		          $http({
		              ContentType:'form-data',
		              method: 'POST',
		              url: 'http://localhost:8000/api/v1/insert', 
		              data: {name: $scope.name, email: $scope.email, password: $scope.password}
		          }).then(function (response) {
		              
		              // on success
		              $scope.form_data_list = response.data;
		              
		          }, function (response) {
		              
		              // on error
		              console.log(response.data,response.status);
		              
		          });
		    };

		    $scope.display = function(){
		    	$http({
		    		method:'GET',
		    		url:'http://localhost:8000/api/v1/display'
		    	}).then(function (response){
		    		$scope.form_data_list =  response.data;
		    		$scope.info=false;
		    		$scope.records=true;
		    	});
		    };
		    $scope.storeId=function(id){
		    	$scope.id=id;
		    	$scope.submit_visibility=true;
		    	$scope.status_visibility=false;
		    	$scope.update_status="";
		    	$scope.previewUpdate();
		    };
		    $scope.previewUpdate=function(){
		    	$http({
		    		method:'POST',
		    		url:'http://localhost:8000/api/v1/previewUpdate',
		    		data: {id:$scope.id}
		    	}).then(function(response){
		    		$scope.preview_form_data_list=response.data;
		    		$scope.edit_name=$scope.preview_form_data_list.name;
		    		$scope.edit_email=$scope.preview_form_data_list.email;
		    		$scope.edit_password=$scope.preview_form_data_list.password;
		    	});
		    };
		  
		    $scope.update = function(){
		    	$http({
		    		method:'POST',
		    		url:'http://localhost:8000/api/v1/edit',
		    		data: {id: $scope.id , name: $scope.edit_name, email: $scope.edit_email, password: $scope.edit_password}
		    	}).then(function(response){
		    		$scope.status_visibility=true;
		    		$scope.submit_visibility=false;
		    		$scope.update_status = response.data;
		    		$scope.display();

		    	});
		    };
		    $scope.delete = function(delete_id,$window){
		    	 $scope.confirmation=window.confirm('Are you absolutely sure you want to delete?');
		    	if($scope.confirmation){ 
			    	$http({
			    		method:'POST',
			    		url:'http://localhost:8000/api/v1/delete',
			    		data: {id:delete_id}
			    	}).then(function(response){
			    		console.log(response.data);
/*			    		window.alert(response.data);
*/			    		$scope.delete_status=response.data;
			    		$scope.display();
			    	});
			    }
			};
		});
	</script>
<!-- 	<script type="text/javascript">
		function setEditId(id){
			$scope.editId=id;
			document.getElementById('editId').value=id;
		}
	</script> -->
</head>
<body>

	<div class="form_row">
	<div  ng-controller="mainController">
 	 <!--  <ul>
	    <li ng-repeat="image in images">
	    @{{image.description}}
	    </li>
	  </ul>  -->
	  <form name="form">
	  	{{csrf_field()}}
	  	<p>Name:<br>
		<input type="text" name="name" ng-model="name" required>
		<span style="color:red" ng-show="form.name.$dirty  && form.name.$invalid">
		<span ng-show="form.name.$error.required">Username is required.</span>
		</span>
		</p>

		<p>Email:<br>
		<input type="email" name="email" ng-model="email" required>
		<span style="color:red" ng-show="form.email.$dirty && form.email.$invalid">
		<span ng-show="form.email.$error.required">Email is required.</span>
		<span ng-show="form.email.$error.email">Invalid email address.</span>
		</span>
		</p>

		<p>Password:<br>
		<input type="password" name="password" ng-model="password" required>
		<span style="color:red" ng-show="form.password.$dirty && form.password.$invalid">
		<span ng-show="form.password.$error.required">Password is required.</span>
		</span>
		</p>
 		<button ng-click="insert()" ng-disabled="form.name.$dirty && form.name.$invalid ||  
		form.email.$dirty && form.email.$invalid || form.password.$dirty && form.password.$invalid">Submit</button>  
		</form><br>

		<button ng-click="display()">Display Records</button>
	  <p ng-show="info">Click Display Records to display list of records without page reload</p>
	  <table class="table table-bordered" ng-show="records">
	  	<thead>
	  		<th>Name</th>
	  		<th>Email</th>
	  		<th>Password</th>
	  		<th>Edit</th>
	  		<th>Delete</th>
	  	</thead>
	  	<tbody ng-repeat="form_data in form_data_list">
		           <tr>
		           <td>@{{ form_data.name }}</td>
		           <td>@{{ form_data.email }}</td>
		           <td>@{{ form_data.password }}</td>	
		           <td><Button type="button" ng-click="storeId(form_data.id)" data-toggle="modal" data-target="#modal_content">
		           	<span class="glyphicon glyphicon-pencil"></span></Button></td>	
		           <td><Button type="button" ng-click="delete(form_data.id,$event)"><span class="glyphicon glyphicon-trash"></span></Button></td>
		           </tr>   
		</tbody>
	    </table>
	    <div class="modal fade" id="modal_content" role="dialog">
	      <div class="modal-dialog">
	      <!-- Modal content-->
	        <div class="modal-content">
	          <div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal">&times;</button>
	            <h4 class="modal-title">Update Data</h4>
	          </div>
	          <div class="modal-body" >
				    <form>
				  	{{csrf_field()}}
				  	<p>Name:<br>
					<input type="text" name="edit_name" ng-model="edit_name" required>
					<span style="color:red" ng-show="form.edit_name.$dirty  && form.edit_name.$invalid">
					<span ng-show="form.edit_name.$error.required">Username is required.</span>
					</span>
					</p>
					<p>Email:<br>
					<input type="email" name="edit_email" ng-model="edit_email" required>
					<span style="color:red" ng-show="form.edit_email.$dirty && form.edit_email.$invalid">
					<span ng-show="form.edit_email.$error.required">Email is required.</span>
					<span ng-show="form.edit_email.$error.email">Invalid email address.</span>
					</span>
					</p>

					<p>Password:<br>
					<input type="password" name="edit_password" ng-model="edit_password" required>
					<span style="color:red" ng-show="form.edit_password.$dirty && form.edit_password.$invalid">
					<span ng-show="form.edit_password.$error.required">Password is required.</span>
					</span>
					</p>
			 		<button class="btn btn-default" ng-show="submit_visibility" ng-click="update()" ng-disabled="form.name.$dirty && form.name.$invalid ||  
					form.email.$dirty && form.email.$invalid || form.password.$dirty && form.password.$invalid">Submit</button>
					</form><br>
					@{{update_status}}&nbsp;&nbsp;<Button class="btn btn-primary"  ng-show="status_visibility" type="button" data-dismiss="modal">ok</Button>
	          </div>
	          <div class="modal-footer">
	            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	          </div>
	        </div>
	      </div>
	    </div>
	</div>
	
</body>
</html>