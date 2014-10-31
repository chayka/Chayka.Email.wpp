angular.module('chayka-email-options-form', ['chayka-options-form', 'chayka-ajax'])
    .controller('test', ['$scope', 'ajax', function($scope, ajax){
        $scope.fields = {
            to: '',
            message: ''
        };

        $scope.validator = null;

        $scope.send = function(){
            if($scope.validator.validateFields()){
                ajax.post('/api/admin-email/test', $scope.fields, {
                    formValidator: $scope.validator,
                    success: function(data){
                        $scope.validator.showMessage(data.message);
                    }
                });
            }
        }
    }]);