<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Faq;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->departments() as $i => $d) {
            Department::firstOrCreate(['slug' => $d['slug']], $d + ['sort' => ($i + 1) * 10]);
        }

        if (Faq::count() === 0) {
            foreach ($this->faqs() as $i => $f) {
                Faq::create($f + ['sort' => $i]);
            }
        }
    }

    private function departments(): array
    {
        return [
            [
                'slug' => 'construction', 'icon' => 'building',
                'name_hy' => 'Շինարարատեխնիկական', 'name_ru' => 'Строительно-техническая', 'name_en' => 'Construction & Technical',
                'description_hy' => 'Շենքերի և շինությունների տեխնիկական վիճակի, կատարված շինարարական աշխատանքների ծավալի, որակի և արժեքի, նախագծային փաստաթղթերին համապատասխանության հետազոտություն։',
                'description_ru' => 'Исследование технического состояния зданий и сооружений, объёма, качества и стоимости выполненных строительных работ, соответствия проектной документации и нормам.',
                'description_en' => 'Examination of the technical condition of buildings, the scope, quality and cost of construction works, and their compliance with design documentation and standards.',
            ],
            [
                'slug' => 'biological', 'icon' => 'dna',
                'name_hy' => 'Կենսաբանական', 'name_ru' => 'Биологическая', 'name_en' => 'Biological',
                'description_hy' => 'Կենսաբանական ծագման օբյեկտների և հետքերի հետազոտություն՝ դրանց բնույթի, ծագման և առանձնահատկությունների պարզաբանման նպատակով։',
                'description_ru' => 'Исследование объектов и следов биологического происхождения для установления их природы, происхождения и характерных признаков.',
                'description_en' => 'Examination of objects and traces of biological origin to determine their nature, origin and distinctive features.',
            ],
            [
                'slug' => 'materials', 'icon' => 'flask',
                'name_hy' => 'Նյութագիտական', 'name_ru' => 'Материаловедческая', 'name_en' => 'Materials Science',
                'description_hy' => 'Նյութերի և արտադրանքի կազմի, հատկությունների և ծագման ուսումնասիրություն, համեմատական հետազոտություն։',
                'description_ru' => 'Изучение состава, свойств и происхождения материалов, веществ и изделий, сравнительные исследования.',
                'description_en' => 'Study of the composition, properties and origin of materials, substances and products, including comparative analysis.',
            ],
            [
                'slug' => 'trace', 'icon' => 'fingerprint',
                'name_hy' => 'Հետքաբանական', 'name_ru' => 'Трасологическая', 'name_en' => 'Trace Evidence',
                'description_hy' => 'Հետքերի (կոշիկների, գործիքների, տրանսպորտային միջոցների և այլն) հետազոտություն, կողպեքների և փակիչների վնասվածքների պարզում, ամբողջը մասերով նույնականացում։',
                'description_ru' => 'Исследование следов (обуви, орудий, транспортных средств и др.), повреждений замков и запорных устройств, установление целого по частям.',
                'description_en' => 'Examination of traces (footwear, tools, vehicles, etc.), damage to locks and seals, and reconstruction of a whole from its parts.',
            ],
            [
                'slug' => 'psychological', 'icon' => 'brain',
                'name_hy' => 'Հոգեբանական', 'name_ru' => 'Психологическая', 'name_en' => 'Psychological',
                'description_hy' => 'Անձի հոգեբանական վիճակի և անհատական առանձնահատկությունների հետազոտություն, ներառյալ երեխաների հետ կապված վեճերով։',
                'description_ru' => 'Исследование психологического состояния и индивидуальных особенностей личности, в том числе по спорам, связанным с детьми.',
                'description_en' => 'Assessment of a person\'s psychological state and individual characteristics, including in disputes involving children.',
            ],
            [
                'slug' => 'handwriting-documents', 'icon' => 'pen',
                'name_hy' => 'Ձեռագրաբանական և փաստաթղթային', 'name_ru' => 'Почерковедческая и техническая экспертиза документов', 'name_en' => 'Handwriting & Document',
                'description_hy' => 'Ձեռագրերի և ստորագրությունների նույնականացում, փաստաթղթերի վավերականության, փոփոխությունների, կնիքների և դրոշմների հետազոտություն։',
                'description_ru' => 'Идентификация почерка и подписей, исследование подлинности документов, внесённых изменений, оттисков печатей и штампов.',
                'description_en' => 'Identification of handwriting and signatures, examination of document authenticity, alterations, seals and stamps.',
            ],
            [
                'slug' => 'cultural', 'icon' => 'landmark',
                'name_hy' => 'Մշակութային', 'name_ru' => 'Культурологическая (искусствоведческая)', 'name_en' => 'Cultural & Art',
                'description_hy' => 'Մշակութային արժեքների, արվեստի գործերի և հնաոճ իրերի հետազոտություն՝ դրանց պատմամշակութային նշանակության և արժեքի որոշմամբ։',
                'description_ru' => 'Исследование культурных ценностей, произведений искусства и антиквариата с определением их историко-культурного значения и стоимости.',
                'description_en' => 'Examination of cultural property, works of art and antiques, determining their historical and cultural significance and value.',
            ],
            [
                'slug' => 'road-traffic', 'icon' => 'car',
                'name_hy' => 'Ճանապարհատրանսպորտային', 'name_ru' => 'Автотехническая (ДТП)', 'name_en' => 'Road Traffic Accidents',
                'description_hy' => 'Ճանապարհատրանսպորտային պատահարի մեխանիզմի, մասնակիցների գործողությունների, տրանսպորտային միջոցների տեխնիկական վիճակի և վնասի գնահատման հետազոտություն։',
                'description_ru' => 'Исследование механизма ДТП, действий его участников, технического состояния транспортных средств и оценка ущерба.',
                'description_en' => 'Reconstruction of road accidents, analysis of the participants\' actions, the technical condition of vehicles and damage assessment.',
            ],
            [
                'slug' => 'accounting', 'icon' => 'calculator',
                'name_hy' => 'Հաշվապահական', 'name_ru' => 'Бухгалтерская', 'name_en' => 'Accounting',
                'description_hy' => 'Հաշվապահական հաշվառման, ֆինանսական գործառնությունների և հաշվետվությունների հետազոտություն, պակասորդի և վնասի չափի որոշում։',
                'description_ru' => 'Исследование бухгалтерского учёта, финансовых операций и отчётности, установление размера недостачи и ущерба.',
                'description_en' => 'Examination of bookkeeping, financial transactions and reporting, determining shortages and the amount of losses.',
            ],
            [
                'slug' => 'commodity', 'icon' => 'box',
                'name_hy' => 'Ապրանքագիտական', 'name_ru' => 'Товароведческая', 'name_en' => 'Commodity',
                'description_hy' => 'Ապրանքների որակի, վիճակի, ծագման, դասակարգման և արժեքի որոշում, ներառյալ ներմուծվող ապրանքների համար։',
                'description_ru' => 'Определение качества, состояния, происхождения, классификации и стоимости товаров, в том числе импортируемых.',
                'description_en' => 'Determining the quality, condition, origin, classification and value of goods, including imported goods.',
            ],
        ];
    }

    private function faqs(): array
    {
        return [
            [
                'question_hy' => 'Ինչպե՞ս ներկայացնել հայտ։', 'question_ru' => 'Как подать заявку?', 'question_en' => 'How do I submit a request?',
                'answer_hy' => 'Լրացրեք կայքի հայտի ձևը կամ զանգահարեք մեզ։ Մենք կկապվենք ձեզ հետ աշխատանքային ժամերին։',
                'answer_ru' => 'Заполните форму заявки на сайте или позвоните нам. Мы свяжемся с вами в рабочее время.',
                'answer_en' => 'Fill in the request form or call us. We will get back to you during working hours.',
            ],
            [
                'question_hy' => 'Ի՞նչ նյութեր են անհրաժեշտ։', 'question_ru' => 'Какие материалы нужны?', 'question_en' => 'What materials are needed?',
                'answer_hy' => 'Անհրաժեշտ նյութերի ցանկը որոշվում է նախնական քննարկման ժամանակ՝ կախված հարցի բնույթից և փորձաքննության տեսակից։',
                'answer_ru' => 'Перечень необходимых материалов определяется на предварительной консультации в зависимости от характера вопроса и вида экспертизы.',
                'answer_en' => 'The list of materials is defined during the initial consultation, depending on the question and the type of expertise.',
            ],
            [
                'question_hy' => 'Ինչպե՞ս են որոշվում ժամկետը և արժեքը։', 'question_ru' => 'Как определяются сроки и стоимость?', 'question_en' => 'How are timing and cost set?',
                'answer_hy' => 'Ժամկետը և արժեքը համաձայնեցվում են անհատապես՝ հետազոտության ծավալից և բարդությունից ելնելով։',
                'answer_ru' => 'Сроки и стоимость согласовываются индивидуально исходя из объёма и сложности исследования.',
                'answer_en' => 'Timing and cost are agreed individually based on the scope and complexity of the research.',
            ],
            [
                'question_hy' => 'Ինչպե՞ս ընտրել փորձաքննության տեսակը։', 'question_ru' => 'Как выбрать вид экспертизы?', 'question_en' => 'How do I choose the type of expertise?',
                'answer_hy' => 'Նկարագրեք ձեր հարցը, և մեր մասնագետները կառաջարկեն համապատասխան ուղղությունը։',
                'answer_ru' => 'Опишите ваш вопрос, и наши специалисты подскажут подходящее направление.',
                'answer_en' => 'Describe your question and our specialists will suggest the right direction.',
            ],
        ];
    }
}
