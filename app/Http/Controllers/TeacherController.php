<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Medoo\Medoo;
use Gregwar\Image\Image;
use Carbon\Carbon;

class TeacherController extends Controller
{
    const PATH = './../storage/app/playlist/';
    const URL = '/storage/playlist/';

    protected $lessonStruct = [
        'name',
        'title',
        'sub_title',
        'icon',
        'is_recommended'
    ];
    protected $categoryStruct = [
        'name',
    ];
    protected $gameStruct = [
        //
    ];

    public function index(Request $request)
    {
        try {
            $data = [];
            $data['lessons'] = app()->mdb->query('
                SELECT <l.id>,<l.icon>,<l.name>,<l.title>,<l.sub_title>,<l.is_recommended>,<c.name> AS `category` FROM `teacher_lessons` `l`
                    JOIN `teacher_lessons_lesson_category` `lc` ON (`lc`.`lesson_id` = `l`.`id`)
                    JOIN `teacher_lessons_category` `c` ON (`c`.`id` = `lc`.`category_id`)
            ')->fetchAll(\PDO::FETCH_CLASS);
            $data['meta']['categories'] = app()->mdb->select('teacher_lessons_category', '*');

            return $data;
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function groups(Request $request)
    {
        try {
            $data = [];
            $lessons = app()->mdb->query('
                SELECT <l.id>,<l.icon>,<l.name>,<l.title>,<l.sub_title>,<l.is_recommended>,<c.name> AS `category` FROM `teacher_lessons` `l`
                    INNER JOIN `teacher_lessons_lesson_category` `lc` ON (`lc`.`lesson_id` = `l`.`id`)
                    INNER JOIN `teacher_lessons_category` `c` ON (`c`.`id` = `lc`.`category_id`)
            ')->fetchAll(\PDO::FETCH_CLASS);
            foreach ($lessons as $lesson) {
                $data['lessons'][$lesson->category][] = $lesson;
            }

            return $data;
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function lesson(Request $request, $id)
    {
        try {
            $lesson = app()->mdb->get('teacher_lessons', '*', [
                'id' => $id,
            ]);
            $gameIds = app()->mdb->select('teacher_lessons_games', 'game_id', [
                'lesson_id' => $id,
            ]);
            $games = [];
            if ($gameIds) {
                $games = app()->mdb->query('SELECT * FROM `playlist` WHERE `id_playlist` IN (' . implode(', ', $gameIds) . ')')->fetchAll(\PDO::FETCH_CLASS);
            }
            $lesson['games'] = $games;
            
            return $lesson;
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function lessonGames(Request $request, $lessonId)
    {
        try {
            $gameIds = app()->mdb->select('teacher_lessons_games', 'game_id', [
                'lesson_id' => $lessonId,
            ]);
            if (is_array($gameIds)) {
                return $gameIds;
                $games = app()->mdb->query('SELECT * FROM `playlist` WHERE `id_playlist` IN (' . implode(', ', $gameIds) . ')')
                    ->fetchAll();
            } else {
                return [];
            }
            return $games;
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function create(Request $request)
    {
        try {
            $data = $request->all();
            if (isset($data['name']) && isset($data['title'])) {
                $lesson = [
                    'name' => trim($data['name']),
                    'title' => trim($data['title']),
                    'sub_title' => trim($data['sub_title']),
                    'icon' => $data['icon'],
                    'is_recommended' => $data['is_recommended'],
                ];

                app()->mdb->insert('teacher_lessons', $lesson);
                $lessonId = app()->mdb->id();

                if (isset($data['category']) && is_numeric($data['category'])) {
                    app()->mdb->insert('teacher_lessons_lesson_category', [
                        'lesson_id' => $lessonId,
                        'category_id' => intval($data['category'])
                    ]);
                }

                if (isset($data['games']) && is_array($data['games'])) {
                    foreach ($data['games'] as $game) {
                        app()->mdb->insert('teacher_lessons_games', [
                            'lesson_id' => $lessonId,
                            'game_id' => intval($game),
                        ]);
                    }
                }
            }
            return app()->mdb->query('
                SELECT <l.id>,<l.icon>,<l.name>,<l.title>,<l.sub_title>,<l.is_recommended>,<c.name> AS `category` FROM `teacher_lessons` `l`
                    INNER JOIN `teacher_lessons_lesson_category` `lc` ON (`lc`.`lesson_id` = `l`.`id`)
                    INNER JOIN `teacher_lessons_category` `c` ON (`c`.`id` = `lc`.`category_id`)
            ')->fetchAll(\PDO::FETCH_CLASS);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $data = $request->all();
            if (isset($data['name']) && isset($data['title'])) {
                $lesson = [
                    'name' => trim($data['name']),
                    'title' => trim($data['title']),
                    'sub_title' => trim($data['sub_title']),
                    'icon' => $data['icon'],
                    'is_recommended' => $data['is_recommended'],
                ];
                app()->mdb->update('teacher_lessons', $lesson, [
                    'id' => $id
                ]);

                if (isset($data['category']) && is_numeric($data['category'])) {
                    app()->mdb->update('teacher_lessons_lesson_category', [
                        'lesson_id' => $id,
                        'category_id' => intval($data['category'])
                    ], [
                        'lesson_id' => $id
                    ]);
                }

                if (isset($data['games']) && is_array($data['games'])) {
                    app()->mdb->delete('teacher_lessons_games', [
                        'lesson_id' => $id
                    ]);
                    foreach ($data['games'] as $game) {
                        app()->mdb->insert('teacher_lessons_games', [
                            'lesson_id' => $id,
                            'game_id' => intval($game),
                        ]);
                    }
                }
            }
            return app()->mdb->query('
                SELECT <l.id>,<l.icon>,<l.name>,<l.title>,<l.sub_title>,<l.is_recommended>,<c.name> AS `category` FROM `teacher_lessons` `l`
                    INNER JOIN `teacher_lessons_lesson_category` `lc` ON (`lc`.`lesson_id` = `l`.`id`)
                    INNER JOIN `teacher_lessons_category` `c` ON (`c`.`id` = `lc`.`category_id`)
            ')->fetchAll(\PDO::FETCH_CLASS);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function remove(Request $request, $id)
    {
        try {
            app()->mdb->delete('teacher_lessons', [
                'id' => $id
            ]);
            return ['message' => 'success'];
        } catch(Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function icon(Request $request, $id)
    {
        try {
            $uniqueId = uniqid();
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $filename = Carbon::now()->format('Ymd') . '_' . $uniqueId . '.' . $extension;
            $path = 'uploads/data/';
            $iconPath = url('http://3.90.154.66/' . $path . $filename);
            $file->move($path, $filename);

            app()->mdb->update('teacher_lessons', [
                'icon' => $iconPath,
            ], [
                'id' => $id
            ]);
            return [
                'url' => $iconPath,
            ];
        } catch(Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function categories(Request $request)
    {
        try {
            return app()->mdb->get('teacher_lessons_category', '*');
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function games(Request $request)
    {
        try {
            return app()->mdb->get('playlist', '*');
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function addCategory(Request $request)
    {
        try {
            $data = [
                'name' => $request->name
            ];
            app()->mdb->insert('teacher_lessons_category', $data);
            return app()->mdb->select('teacher_lessons_category', '*');
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function updateCategory(Request $request, $id)
    {
        try {
            $category = app()->mdb->get('teacher_lessons_category', '*', [
                'id' => $id
            ]);
            if ($category) {
                $requestData = $request->all();
                $data = [];
                foreach ($this->categoryStruct as $field => $default) {
                    if (isset($requestData[$field])) {
                        $data[$field] = $requestData[$field];
                    }
                }
                app()->mdb->update('teacher_lessons_category', $data, [
                    'id' => $id
                ]);
            }
            return app()->mdb->select('teacher_lessons_category', '*');
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function removeCategory(Request $request, $id)
    {
        try {
            app()->mdb->delete('teacher_lessons_category', [
                'id' => $id
            ]);
            return app()->mdb->select('teacher_lessons_category', '*');
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
