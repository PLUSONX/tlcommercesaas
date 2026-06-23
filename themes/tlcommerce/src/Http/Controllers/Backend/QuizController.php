<?php

namespace Theme\TLCommerce\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Core\Models\Language;
use Plugin\TlcommerceCore\Models\Product;
use Theme\TLCommerce\Models\QuizAnswer;
use Theme\TLCommerce\Models\QuizQuestion;
use Theme\TLCommerce\Repositories\QuizRepository;

class QuizController extends Controller
{
    protected $quiz_repository;

    public function __construct(QuizRepository $quiz_repository)
    {
        if (!isActivePluging('tlecommercecore')) {
            abort(403, 'Tlcommerce plugin is required');
        }

        $this->quiz_repository = $quiz_repository;
    }

    protected function activeLanguages()
    {
        return Language::where('status', config('settings.general_status.active'))
            ->select('id', 'name', 'code', 'native_name')
            ->get();
    }

    protected function resolveLang(Request $request): string
    {
        return $request->input('lang', getDefaultLang());
    }

    public function index()
    {
        return view('theme/tlcommerce::backend.quiz.index')->with([
            'quizzes' => $this->quiz_repository->listQuizzes(),
        ]);
    }

    public function create()
    {
        return view('theme/tlcommerce::backend.quiz.form', [
            'quiz' => null,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'step_style' => 'nullable|in:wizard,scroll,cards',
        ]);

        $quiz = $this->quiz_repository->storeQuiz($request);
        toastNotification('success', translate('Quiz created successfully'));

        return redirect()->route('theme.tlcommerce.quiz.questions', $quiz->id);
    }

    public function edit($id, Request $request)
    {
        $lang = $this->resolveLang($request);

        return view('theme/tlcommerce::backend.quiz.form', [
            'quiz' => $this->quiz_repository->findQuiz($id),
            'lang' => $lang,
            'languages' => $this->activeLanguages(),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'step_style' => 'nullable|in:wizard,scroll,cards',
        ]);

        $this->quiz_repository->updateQuiz($request);
        toastNotification('success', translate('Quiz updated successfully'));

        return redirect()->route('theme.tlcommerce.quiz.edit', [
            'id' => $request->input('id'),
            'lang' => $this->resolveLang($request),
        ]);
    }

    public function delete(Request $request)
    {
        $this->quiz_repository->deleteQuiz($request->input('id'));
        toastNotification('success', translate('Quiz deleted successfully'));

        return redirect()->route('theme.tlcommerce.quiz.list');
    }

    public function updateStatus(Request $request)
    {
        $this->quiz_repository->updateQuizStatus($request->input('id'));
        toastNotification('success', translate('Status updated successfully'));

        return redirect()->back();
    }

    public function questions($id, Request $request)
    {
        $lang = $this->resolveLang($request);
        $quiz = $this->quiz_repository->findQuiz($id);

        return view('theme/tlcommerce::backend.quiz.questions')->with([
            'quiz' => $quiz,
            'lang' => $lang,
            'languages' => $this->activeLanguages(),
        ]);
    }

    public function storeQuestion(Request $request)
    {
        $request->validate([
            'quiz_id' => 'required|integer',
            'question_text' => 'required|string',
            'question_type' => 'required|in:radio,checkbox,dropdown,image_select',
        ]);

        $this->quiz_repository->storeQuestion($request);
        toastNotification('success', translate('Question added successfully'));

        return redirect()->back();
    }

    public function updateQuestion(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'question_text' => 'required|string',
            'question_type' => 'required|in:radio,checkbox,dropdown,image_select',
        ]);

        $this->quiz_repository->updateQuestion($request);
        toastNotification('success', translate('Question updated successfully'));

        return redirect()->back();
    }

    public function deleteQuestion(Request $request)
    {
        $this->quiz_repository->deleteQuestion($request->input('id'));
        toastNotification('success', translate('Question deleted successfully'));

        return redirect()->back();
    }

    public function reorderQuestions(Request $request)
    {
        $this->quiz_repository->reorderQuestions($request);

        return response()->json(['success' => true]);
    }

    public function updateAnswerDefaults(Request $request, $id)
    {
        $defaults = json_decode($request->input('answer_defaults_json', '{}'), true);
        if (!is_array($defaults)) {
            $defaults = [];
        }

        $this->quiz_repository->updateAnswerDefaults($id, $defaults);
        toastNotification('success', translate('Default answer styling saved successfully'));

        return redirect()->back();
    }

    public function answers($questionId, Request $request)
    {
        $lang = $this->resolveLang($request);
        $question = QuizQuestion::with([
            'answers.quiz_answer_translations',
            'quiz_question_translations',
            'quiz',
        ])->findOrFail($questionId);

        return view('theme/tlcommerce::backend.quiz.answers')->with([
            'question' => $question,
            'quiz' => $question->quiz,
            'lang' => $lang,
            'languages' => $this->activeLanguages(),
        ]);
    }

    public function updateQuestionAnswerLayout(Request $request, $questionId)
    {
        $answersConfig = json_decode($request->input('answer_layout_json', '{}'), true);
        if (!is_array($answersConfig)) {
            $answersConfig = [];
        }

        $this->quiz_repository->updateQuestionAnswerLayout($questionId, $answersConfig);
        toastNotification('success', translate('Question answer styling saved successfully'));

        return redirect()->back();
    }

    public function storeAnswer(Request $request)
    {
        $request->validate([
            'question_id' => 'required|integer',
            'answer_text' => 'required|string|max:500',
            'answer_image' => 'nullable|string|max:255',
            'answer_description' => 'nullable|string|max:2000',
        ]);

        $this->quiz_repository->storeAnswer($request);
        toastNotification('success', translate('Answer added successfully'));

        return redirect()->back();
    }

    public function updateAnswer(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'answer_text' => 'required|string|max:500',
            'answer_image' => 'nullable|string|max:255',
            'edit_answer_image' => 'nullable|string|max:255',
            'answer_description' => 'nullable|string|max:2000',
        ]);

        $this->quiz_repository->updateAnswer($request);
        toastNotification('success', translate('Answer updated successfully'));

        return redirect()->back();
    }

    public function deleteAnswer(Request $request)
    {
        $this->quiz_repository->deleteAnswer($request->input('id'));
        toastNotification('success', translate('Answer deleted successfully'));

        return redirect()->back();
    }

    public function scores($answerId)
    {
        $answer = QuizAnswer::with(['productScores.product', 'question.quiz'])->findOrFail($answerId);

        return view('theme/tlcommerce::backend.quiz.scores')->with([
            'answer' => $answer,
            'question' => $answer->question,
            'quiz' => $answer->question->quiz,
        ]);
    }

    public function saveScores(Request $request)
    {
        $request->validate([
            'answer_id' => 'required|integer',
            'scores' => 'nullable|array',
            'scores.*.product_id' => 'required|integer',
            'scores.*.score' => 'nullable|numeric',
        ]);

        $this->quiz_repository->saveProductScores($request);
        toastNotification('success', translate('Product scores saved successfully'));

        return redirect()->back();
    }

    public function searchProducts(Request $request)
    {
        $search = $request->input('q', '');

        $products = Product::where('status', config('settings.general_status.active'))
            ->when($search, fn ($q) => $q->where('name', 'like', '%' . $search . '%'))
            ->orderBy('name')
            ->limit(20)
            ->get(['id', 'name']);

        $results = $products->map(fn ($p) => [
            'id' => $p->id,
            'text' => $p->translation('name', getLocale()),
        ]);

        return response()->json(['results' => $results]);
    }
}
