var app = angular.module('quizApp', []);

app.directive('quiz', function($http) {
	return {
		restrict: 'AE',
		scope: {},
		templateUrl: '/template.html',
		link: function(scope, elem, attrs) {
			scope.start = function() {
				scope.getRandomItemUrl = '/exam/random-item';
				scope.getRandomItemParams = {};

				var typeGroup = $('#type-group').val();
				if (typeGroup != '') {
					typeGroup = typeGroup.split('|');

					scope.getRandomItemUrl += '/'+typeGroup[0];
					scope.getRandomItemParams = {groupName: typeGroup[1]};
				}

				scope.id = 0;
				scope.quizOver = false;
				scope.inProgress = true;
				scope.getQuestion();
			};

			scope.reset = function() {
				scope.inProgress = false;
			}

			scope.getQuestion = function() {
				$http({
					method: 'GET',
					url: scope.getRandomItemUrl,
					params: scope.getRandomItemParams
				}).success(function (response) {
					scope.question = response.question;
					scope.questionId = response.question_id;
					scope.picture = response.picture;
					scope.options = response.options;
					scope.picture = response.picture;
					scope.answerMode = true;
				});
			};

			scope.checkAnswer = function() {
				if(!$('input[name=answer]:checked').length) return;
				var ans = $('input[name=answer]:checked').val();
				scope.correctAns = ans;

				$http({
					url: '/exam/save-practice/' + scope.questionId,
					method: 'GET',
					params: {
                        'userName': 'felho',
                        'isRightAnswer': ans
                    }
				}).success(function (response) {
                    scope.answerMode = false;
                });
			};

			scope.nextQuestion = function() {
				scope.id++;
				scope.getQuestion();
			}

			scope.reset();
		}
	}
});