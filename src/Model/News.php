<?php
namespace Model;

use Core\DAOException;
use DateTime;
use Model\Actualite;
use PicoDb\Table;

class News extends Base
{

    /**
     * SQL table name
     *
     * @var string
     */
    const TABLE = 'cep_news';

    public function query($disabled = false)
    {
        $query = $this->db->table(self::TABLE)->columns('id', 'title', 'content', 'date_start', 'date_update', 'date_end', 'state');
        if (! $disabled) {
            $query->eq('state', 1)
                ->gte('date_end', time())
                ->lte('date_start', time());
        }
        return $query;
    }

    /**
     * Return true if a news exists for a given project
     *
     * @access public
     * @param integer $news_id
     *            News id
     * @return boolean
     */
    public function exists($news_id)
    {
        return $this->db->table(self::TABLE)
            ->eq('id', $news_id)
            ->exists();
    }

    public function getLastUpdate()
    {
        $lastUpdate = $this->db->table(self::TABLE)
            ->columns('date_update')
            ->eq('state', 1)
            ->gte('date_end', time())
            ->lte('date_start', time())
            ->orderBy('date_update', Table::SORT_DESC)
            ->findOne();
        if (null == $lastUpdate) {
            return time();
        }
        return $lastUpdate['date_update'];
    }

    /**
     * Get a news by the id
     *
     * @access public
     * @param integer $news_id
     *            News id
     * @return array
     */
    public function getById($news_id, $disabled = false)
    {
        return $this->query($disabled)
            ->eq('id', $news_id)
            ->findOne();
    }

    public function getAll($offset, $limit, $disabled = false)
    {
        return $this->query($disabled)
            ->orderBy('date_start', Table::SORT_DESC)
            ->offset($offset)
            ->limit($limit)
            ->findAll();
    }

    public function getAllForFeeds($limit, $excerpt, $feedType)
    {
        $news = $this->query(false)
            ->orderBy('date_start', Table::SORT_DESC)
            ->offset(0)
            ->limit($limit)
            ->findAll();
        $feeds = array();
        foreach ($news as $element) {
            $newItem = new \FeedItem($feedType);
            $newItem->setTitle($element['title']);
            $newItem->setLink(SITE_PATH . 'evenement-' . $element['id'] . '.html');
            $newItem->setDate($element['date_start']);
            $element = array_merge($element, $this->computeExceprt($element));
            if ($excerpt) {
                $element['excerpt'] = preg_replace('!<a href="([^h].+)"!iU', '<a href="' . SITE_PATH . '$1"', $element['excerpt']);
            }
            $newItem->setDescription($excerpt ? $element['excerpt'] : $element['content']);
            $newItem->addElement('author', 'CEP Saint-Maur');
            $newItem->addElement('guid', SITE_PATH . 'actualite-' . $element['id'] . '.html', array(
                'isPermaLink' => 'true'
            ));
            $feeds[] = $newItem;
        }
        return $feeds;
    }

    public function computeExceprt($news)
    {
        $pattern = '\[Lire la suite\]';
        $excerpt = preg_replace('!^(.*)(?:<br>)?\s*' . $pattern . '(.*)$!isU', '$1<br /><a href="evenement-' . $news['id'] . '.html" title="Lire la suite' . (! empty($news['title']) ? ' de &quot;' . str_replace('"', '&quot;', $news['title']) . '&quot;' : '') . '" class="liresuite">Lire la suite...</a></p>', $news['content']);
        $content = preg_replace('!^(.*)' . $pattern . '\s*(?:<br>)?(.*)$!is', '$1$2', $news['content']);
        return array(
            'content_raw' => $news['content'],
            'content' => $content,
            'excerpt' => $excerpt
        );
    }

    /**
     * Prepare data to be stored on db
     *
     * @param array $values            
     * @param
     *            array
     */
    public function prepareUpdate(array $values)
    {
        $values['id'] = intval(@$values['id']);
        $values['date_start'] = isset($values['date_start']) ? DateTime::createFromFormat('d/m/Y', $values['date_start'])->getTimestamp() : time();
        $values['date_update'] = time();
        $values['date_end'] = isset($values['date_end']) ? DateTime::createFromFormat('d/m/Y', $values['date_end'])->getTimestamp() : time() + 3600 * 24 * 30; // +1 month
        $values['state'] = intval(@$values['state']);
        unset($values['sendNews']);
        return $values;
    }

    /**
     * Update a news
     *
     * @access public
     * @param array $values
     *            Form values
     * @return bool
     */
    public function update(array $values)
    {
        $values = $this->prepareUpdate($values);
        if (! isset($values['id']) || empty($values['id']) || 0 == $values['id']) {
            unset($values['id']);
            $res = $this->persist(self::TABLE, $values);
            $values['id'] = $res;
        }
        else {
            $res = $this->db->table(self::TABLE)
                ->eq('id', $values['id'])
                ->save($values);
        }
        if (! $res) {
            throw new DAOException('Erreur lors de la sauvegarde d\'une news');
        }
        return $values['id'];
    }

    /**
     * Remove a news
     *
     * @access public
     * @param integer $news_id
     *            News id
     * @return bool
     */
    public function remove($news_id)
    {
        $this->db->startTransaction();
        
        if (! $this->db->table(self::TABLE)
            ->eq('id', $news_id)
            ->remove()) {
            $this->db->cancelTransaction();
            return false;
        }
        
        $this->db->closeTransaction();
        
        return true;
    }

    public function getList($page = 1, $admin = false, $nbPerPage = NbParPage, $appellationElement = 'événement')
    {
        $nbElement = $this->calculNbActualites($admin);
        $nbPage = calculNbPage($nbPerPage, $nbElement);
        $actualites = $this->findActualites($page, $admin, $nbPerPage, $admin);
        $params = array(
            'actualites' => $actualites,
            'nbPage' => $nbPage,
            'nbMaxLienPagination' => NbMaxLienPagination,
            'page' => $page,
            'nbElement' => $nbElement,
            'appellationElement' => $appellationElement,
            'UrlTri' => '#actualites'
        );
        return $params;
    }

    public function findActualiteById($id, $disabled = false)
    {
        $data = $this->getById($id, $disabled);
        if (null != $data) {
            $data = array_merge($data, $this->computeExceprt($data));
            $data = new Actualite($data);
        }
        return $data;
    }

    public function findActualites($page = 1, $disabled = false, $nbParPage = NbParPage, $memeLesInvisibles = false)
    {
        $data = $this->getAll((($page - 1) * $nbParPage), $nbParPage, $disabled);
        $news = array();
        if (! empty($data)) {
            foreach ($data as $params) {
                $params = array_merge($params, $this->computeExceprt($params));
                $news[] = new Actualite($params);
            }
        }
        return $news;
    }

    public function findAllActualites($limit, $disabled = false)
    {
        $data = $this->getAll(0, $limit, $disabled);
        $news = array();
        if (! empty($data)) {
            foreach ($data as $params) {
                $params = array_merge($params, $this->computeExceprt($params));
                $news[] = new Actualite($params);
            }
        }
        return $news;
    }

    public function calculNbActualites($disabled = false)
    {
        return $this->query()->count();
    }
}
