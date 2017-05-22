var app = angular.module('quizApp', []);

app.directive('quiz', function($http, $document) {
	return {
		restrict: 'AE',
		scope: {},
		templateUrl: '/template.html',
		link: function(scope, elem, attrs) {
			scope.setUsername = function() {
				scope.username = $('input[name=username]').val();
				scope.isUserSet = true;
			}

			scope.start = function() {
				scope.getRandomItemUrl = '/exam/random-item';
				scope.getRandomItemParams = {userName: scope.username};

				var typeGroup = $('#type-group').val();
				if (typeGroup != '') {
					typeGroup = typeGroup.split('|');

					scope.getRandomItemUrl += '/'+typeGroup[0];
					scope.getRandomItemParams = {groupName: typeGroup[1], userName: scope.username};
				}

				scope.id = 0;
				scope.quizOver = false;
				scope.inProgress = true;
				scope.getQuestion();
			};

			scope.reset = function() {
				scope.inProgress = false;
			}

			scope.manageShortcuts = function($event) {
				if (scope.answerMode) {
					switch ($event.keyCode) {
						case 49:
						case 50:
						case 51:
							var input = $($('input[name=answer]').get($event.keyCode-49));
							input.prop("checked", true);
							angular.element(input).click();
					}
					if ($event.keyCode == 39) {
						$('#skipButton').click();
					}
				} else {
					if ($event.keyCode == 39) {
						$('#nextButton').click();
					}
				}
			}
			$document.bind('keyup', scope.manageShortcuts);

			scope.skip = function() {
				$http({
					url: '/exam/save-practice/' + scope.questionId,
					method: 'GET',
					params: {
                        'userName': scope.username,
                        'isRightAnswer': 2
                    }
				}).success(function (response) {
                    scope.getQuestion();
                });
			}

			scope.getQuestion = function() {
				$http({
					method: 'GET',
					url: scope.getRandomItemUrl,
					params: scope.getRandomItemParams
				}).success(function (response) {
					scope.question = response.question;
					scope.questionId = response.question_id;
					scope.origQuestionNum = response.orig_csv_id-1;
					scope.picture = response.picture;
					scope.options = response.options;
					scope.picture = response.picture;
					scope.answerMode = true;

					scope.startTimer();
				});
			};

			scope.startTimer = function () {
				if (scope.timer) {
					clearInterval(scope.timer);
				}

				scope.countdown = 60;
				scope.timer = setInterval(function () {
					if (!scope.answerMode || scope.countdown <= 1) {
						clearInterval(scope.timer);
					}
					scope.countdown--;
					scope.$apply();
				}, 1000);
			}

			scope.checkAnswer = function() {
				if(!$('input[name=answer]:checked').length) return;
				var ans = $('input[name=answer]:checked').val();
				scope.correctAns = ans;

				$http({
					url: '/exam/save-practice/' + scope.questionId,
					method: 'GET',
					params: {
                        'userName': scope.username,
                        'isRightAnswer': scope.correctAns == 'true' ? 1 : 0,
                    }
				}).success(function (response) {
                    scope.answerMode = false;
                });
			};

			scope.reset();
		}
	}
});