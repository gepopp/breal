<?php

namespace App\Livewire\Parts;

use Exception;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class LinkeInWall extends Component
{
    public ?User $user = null;

    public function mount()
    {
        $users = User::whereNotNull('linkedin')->get();

        foreach ($users as $user) {
            if (Carbon::make($user->linkedin['expiresIn'])->isFuture()) {
                $this->user = $user;
                break;
            }
        }
    }

    public function linkedin_posts()
    {
        // 6975493",

        $response = Http::withHeaders([
            'X-Restli-Protocol-Version' => '2.0.0',
            'LinkedIn-Version' => '202504',
            'X-RestLi-Method' => 'FINDER',
            'Authorization' => "Bearer {$this->user->linkedin['token']}",
            'Accept' => 'application/json',
        ])->get('https://api.linkedin.com/rest/'.'posts', [
            'q' => 'author',
            'author' => 'urn:li:organization:11490371',
            'count' => 3,
            'sortBy' => 'CREATED',
        ]);

        //        $response = Http::withHeaders([
        //            'X-Restli-Protocol-Version' => '2.0.0',
        //            'LinkedIn-Version' => '202404',
        //            'Authorization' => "Bearer {$this->user->linkedin['token']}",
        //        ])->get('https://api.linkedin.com/v2/organizationAcls', [
        //            'q' => 'roleAssignee',
        //            'role' => 'ADMINISTRATOR',
        //            'projection' => '(elements*(*,organization~(id,localizedName,logoV2(original~:playableStreams))))'
        //        ]);

        if (! $response->successful()) {
            Log::critical($response->body());

            return [];
        }

        // Erfolgreiche Antwort verarbeiten
        $data = $response->json();
        $posts = [];

        if (isset($data['elements']) && is_array($data['elements'])) {
            foreach ($data['elements'] as $post) {
                $posts[] = $this->getPostDetails($post['id']);
            }
        }

        return $posts;
    }

    public function getPostDetails(string $postId)
    {
        try {
            // Codierung der Post-ID falls nötig
            $encodedPostId = urlencode($postId);

            $response = Http::withHeaders([
                'X-Restli-Protocol-Version' => '2.0.0',
                'LinkedIn-Version' => '202504',
                'Authorization' => "Bearer {$this->user->linkedin['token']}",
                'Accept' => 'application/json',
            ])->get("https://api.linkedin.com/v2/ugcPosts/{$encodedPostId}", [
                'projection' => '(id,author,created,lastModified,visibility,specificContent(com.linkedin.ugc.ShareContent(shareCommentary,shareMediaCategory,media(*(media~:playableStreams,thumbnails,status,originalUrl,*)))))',
            ]);

            if (! $response->successful()) {
                return [
                    'success' => false,
                    'message' => __('linkedin.api_error', ['status' => $response->status()]),
                    'details' => $response->json() ?: $response->body(),
                ];
            }

            $postData = $response->json();

            // Transformiere die rohen Daten in ein besser strukturiertes Format
            $formattedPost = [
                'id' => $postData['id'],
                'author' => $postData['author'],
                'visibility' => $postData['visibility'] ?? 'UNKNOWN',
                'created_at' => date('Y-m-d H:i:s', $postData['createdAt'] / 1000),
                'published_at' => date('Y-m-d H:i:s', $postData['publishedAt'] / 1000),
                'last_modified_at' => date('Y-m-d H:i:s', $postData['lastModifiedAt'] / 1000),
                'text' => $postData['commentary'] ?? null,
                'lifecycle_state' => $postData['lifecycleState'] ?? null,
                'is_edited' => $postData['lifecycleStateInfo']['isEditedByAuthor'] ?? false,
            ];

            // Content und Medien extrahieren
            if (isset($postData['content'])) {
                if (isset($postData['content']['media'])) {
                    $formattedPost['media'] = $postData['content']['media'];
                }

                if (isset($postData['content']['article'])) {
                    $formattedPost['article'] = $postData['content']['article'];
                }
            }

            return $formattedPost;

        } catch (Exception $e) {
            Log::error("LinkedIn Post-Details Exception: {$e->getMessage()}", [
                'post_id' => $postId,
                'trace' => $e->getTraceAsString(),
            ]);

            return [];
        }
    }

    public function render()
    {
        if (! is_null($this->user)) {
            $posts = $this->linkedin_posts();
        }

        return view('livewire.parts.linke-in-wall');
    }
}
