/**
 * Created by kudlaty01 on 08.08.18.
 */
Frontend = {
    init: function () {
        const ViewModel = function () {
            const self = this;
            self.pollId = ko.observable();
            self.question = ko.observable();
            self.answers = ko.observableArray();
            self.error = ko.observable();
            self.answered = ko.pureComputed(()=> !self.error() && !ko.utils.arrayFilter(self.answers(), a=>!a.total()).length);
            self.getPoll = function () {
                self.error(null);
                ajax.post('index/getPoll', {}, function (response) {
                    const jsonData = JSON.parse(response);
                    if (jsonData.error) {
                        self.error(jsonData.error);
                    }
                    else {
                        self.pollId(jsonData.poll.poll_id)
                            .question(jsonData.poll.question)
                            .answers(jsonData.poll.answers.map(a=>new AnswerModel(a)))
                    }
                });
            };
            self.vote = function () {
                self.error(null);
                ajax.post('index/vote', {poll_id: self.pollId(), answer_id: this.answerId()}, function (response) {
                    const jsonData = JSON.parse(response);
                    if (jsonData.error) {
                        self.error(jsonData.error);
                    }
                    else {
                        ko.utils.arrayForEach(self.answers(), function (item) {
                            item.total(jsonData.totals[item.answerId()]);
                        });
                    }


                });

            };
            self.getPoll();
        };
        const AnswerModel = function (data) {
            const self = this;
            self.answerId = ko.observable();
            self.answerText = ko.observable();
            self.total = ko.observable();
            if (data) {
                self.answerId(data.answer_id)
                    .answerText(data.text);
            }
        };

        ko.applyBindings(new ViewModel());
    }
}
