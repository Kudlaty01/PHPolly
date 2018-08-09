/**
 * Created by kudlaty01 on 07.08.18.
 */

Polls = {
    PollModel: function (data) {
        const self = this;
        self.pollId = ko.observable();
        self.question = ko.observable();
        self.expirationDate = ko.observable();
        self.editionUrl = ko.pureComputed(()=>'/polls/edit/' + self.pollId());
        if (data) {
            self.pollId(data.poll_id)
                .question(data.question)
                .expirationDate(data.expirationDate);
        }
        return self;
    },
    AnswerModel: function (data) {
        const self = this;
        self.answerId = ko.observable();
        self.text = ko.observable();
        self.invalid = ko.pureComputed(()=>!self.text());
        if (data) {
            self.answerId(data.answer_id)
                .text(data.text);
        }
    },
    list: function () {
        const ViewModel = function () {
            var self = this;
            self.total = ko.observable();
            self.page = ko.observable(0);
            self.pageSize = ko.observable(20);
            self.polls = ko.observableArray();
            self.editItem = function () {
                console.log(this.pollId());
            };
            self.removeItem = function () {
                const item = this;
                console.log(item);
                if (confirm("Are you sure you want to delete this item?")) {
                    ajax.post('polls/remove', {id: item.pollId()}, function (response) {
                        const data = JSON.parse(response);
                        alert(data.message);
                        if (data.success) {
                            self.polls.remove(item);
                        }
                    });
//                alert("Then shall it be deleted!");
                }
            };
            self.reload = function () {
                ajax.post('polls/list', {
                    limit: self.pageSize(),
                    offset: self.pageSize() * self.page()
                }, function (response) {
                    const data = JSON.parse(response);
                    self.polls(data.data.map(d => new Polls.PollModel(d)));
                });
            };
            self.reload();
        };
        ko.applyBindings(new ViewModel());
    },
    edit: function (data) {
        if (typeof data == "string") {
            data = JSON.parse(data);
        }
        const viewModel = Polls.PollModel(data);
        viewModel.answers = ko.observableArray();
        viewModel.addAnswer = function () {
            viewModel.answers.push(new Polls.AnswerModel());
        };
        viewModel.removeAnswer = function () {
            if (viewModel.answers().length <= 2) {
                alert("There must be at least two answers per poll!");
                return;
            }
            if (this.answerId()) {
                alert("Cannot remove already saved poll answers - somebody might have answered the poll with them!");
                return;
            }
            viewModel.answers.remove(this);
        };
        viewModel.invalid = ko.pureComputed(()=>
            !viewModel.question()
            || viewModel.answers().length < 2
            || (viewModel.expirationDate() && !/^20(\d){2}-(0[0-9]|1[0-2])-([0-2][0-9]|3[01]) ([01][0-9]|2[0-4])(:[0-5][0-9]){2}/.test(viewModel.expirationDate()) )
        );
        if (data.answers) {
            viewModel.answers(data.answers.map(a=>new Polls.AnswerModel(a)));
        }
        ko.applyBindings(viewModel);

    }
};
