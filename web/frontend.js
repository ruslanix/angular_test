angular.module('myApp', []).controller('userCtrl', function($scope, $http) {

    $scope.editImage = function(id) {
        if (id == 'new') {
            $scope.edit = false;
            $scope.incomplete = true;
            $scope.label = '';
            $scope.file = false;
            $scope.image_preview = '';
            $scope.filename = 'new.jpg';
        } else {
            $scope.edit = true;
            $scope.curr_image = $scope.images[id];
            $scope.label = $scope.images[id].label;
            $scope.file = false;
            $scope.filename = $scope.curr_image.filename;
        }
    };

    $scope.deleteImage = function(id) {

        if (!confirm('Are you sure ?')) {
            return;
        }

        var url = '/images/' + $scope.images[id].id + '.json?XDEBUG_SESSION_START=netbeans-xdebug';

        $http.delete(url)
        .success(function(data) {
            $scope.resetFormFields();
            $scope.loadImages();
            alert('Images updated');
        })
        .error(function(data) {
            alert('Some error occured, see console');
            console.log(data);
        });
    };

    $scope.validateFields = function() {
        $scope.incomplete = false;
        if (!$scope.label.length) {
            $scope.incomplete = true;
        }

        if (!$scope.edit && !$scope.file) {
            $scope.incomplete = true;
        }
    };

    $scope.loadImages = function() {
        $http.get("/images.json")
            .success(function(response) {$scope.images = response.items;})
            .error(function(response) {alert("Some error during loading images, see console"); console.log(response)});
    };

    $scope.uploadedFile = function(element) {
        $scope.$apply(function($scope) {
            $scope.file = element.files.length ? element.files[0] : false;
        });
    };

    $scope.addFile = function() {
        var fd = new FormData();
        var url = $scope.edit ? '/images/' + $scope.curr_image.id + '.json?XDEBUG_SESSION_START=netbeans-xdebug' : '/images.json?XDEBUG_SESSION_START=netbeans-xdebug';

        fd.append('image[file]', $scope.file);
        fd.append('image[label]', $scope.label);

        $http.post(url, fd, {
            withCredentials : false,
            headers : {
            'Content-Type' : undefined
            },
            transformRequest : angular.identity
            })
        .success(function(data) {
            $scope.resetFormFields();
            $scope.loadImages();
            alert('Images updated');
        })
        .error(function(data) {
            alert('Some error occured, see console');
            console.log(data);
        });
    };

    $scope.resetFormFields = function() {
        $scope.label = '';
        $scope.file = false;

        $scope.edit = false;
        $scope.incomplete = false;
        $scope.curr_image = false;
        $scope.filename = 'new.jpg';
    };
    
    $scope.loadImages();

    $scope.$watch('label', function() {$scope.validateFields();});
    $scope.$watch('file', function() {$scope.validateFields();});

    $scope.resetFormFields();
});