<?php
namespace Model;

use Service\DAO;
use Model\Actualite;
use Core\DAOException;

class ActualiteDAOImpl extends DAO implements ActualiteDAO
{

    public function getList($page = 1, $admin = false, $nbPerPage = NbParPage, $appellationElement = 'événement')
    {
        $nbElement = $this->calculNbActualites($admin);
        $nbPage = calculNbPage($nbPerPage, $nbElement);
        $actualites = $this->findActualites($page, $admin, $nbPerPage, $admin);
        if (NULL != $actualites && count($actualites) > 0) {
            foreach ($actualites as $actualite) {
                $actualite->computeExtrait();
            }
        }
        
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

    /**
     * Récupère un élement par son Id
     *
     * @access public
     * @param integer $id            
     * @return Type
     */
    public function findActualiteById($id, $memeLesDesactives = true)
    {
        $qry = 'SELECT id, titre, contenu, date_debut AS dateDebut, date_modif AS dateModif, date_fin AS dateFin, etat
				FROM ' . PREFIXE_DB . 'actualite
				WHERE ' . (! $memeLesDesactives ? 'etat>=1 AND ' : '') . 'id = ?
				LIMIT 0,1';
        $params = array(
            $id
        );
        $elements = $this->select($qry, $params, '\\Model\\Actualite');
        if (NULL == $elements || count($elements) <= 0) {
            return NULL;
        }
        $actualite = $elements[0]; // ->prepareToPrint();
        return $actualite;
    }

    /**
     * Récupère tous les éléments selon des critères
     *
     * @access public
     * @param integer $page            
     * @param string $tri            
     * @param boolean $memeLesPlusDisponibles            
     * @return array
     */
    public function findActualites($page = 1, $memeLesDesactives = false, $nbParPage = NbParPage, $memeLesInvisibles = false)
    {
        $qry = 'SELECT id, titre, contenu, date_debut AS dateDebut, date_modif AS dateModif, date_fin AS dateFin, etat
				FROM ' . PREFIXE_DB . 'actualite
				' . (! $memeLesDesactives ? ' WHERE (etat=1' . ($memeLesInvisibles ? ' OR etat=2' : '') . ') AND date_fin >= CURDATE() AND date_debut <= CURDATE() ' : '') . '
				ORDER BY date_debut DESC
				LIMIT ' . (($page - 1) * $nbParPage) . ', ' . $nbParPage;
        $params = NULL;
        $elements = $this->select($qry, $params, '\\Model\\Actualite');
        if (NULL == $elements || count($elements) <= 0) {
            return NULL;
        }
        return $elements;
    }

    /**
     * Récupère tous les éléments selon des critères
     *
     * @access public
     * @param integer $page            
     * @param string $tri            
     * @param boolean $memeLesPlusDisponibles            
     * @return array
     */
    public function findAllActualites($limit, $memeLesFinies = true)
    {
        $qry = 'SELECT id, titre, contenu, date_debut AS dateDebut, date_modif AS dateModif, date_fin AS dateFin, etat
				FROM ' . PREFIXE_DB . 'actualite
				WHERE etat>=1 ' . (! $memeLesFinies ? ' AND (date_fin >= CURDATE() AND date_debut <= CURDATE()) ' : '') . '
				ORDER BY date_debut DESC
				LIMIT 0, ' . $limit;
        $params = NULL;
        $elements = $this->select($qry, $params, '\\Model\\Actualite');
        if (NULL == $elements || count($elements) <= 0) {
            return NULL;
        }
        return $elements;
    }

    /**
     * Calcul le nb d'éléments
     *
     * @see ActualiteDAO::calculNbElements()
     */
    public function calculNbActualites($memeLesDesactives = false)
    {
        $qry = 'SELECT COUNT(id) AS nb
				FROM ' . PREFIXE_DB . 'actualite
				' . (! $memeLesDesactives ? ' WHERE etat=1 AND date_fin >= CURDATE() AND date_debut <= CURDATE() ' : '');
        $params = array(
            0
        );
        $requete = $this->query($qry, $params);
        $nbElement = $requete->fetchColumn();
        return $nbElement;
    }

    /**
     * Ajoute ou met à jour un élément dans la BDD (id NULL ou à 0 pour ajouter)
     *
     * @access public
     * @param Element $element            
     * @return Element
     */
    public function updateActualite(Actualite $actualite)
    {
        // $actualite->prepareToSave();
        // -- Creation
        $params = array();
        if (0 == $actualite->getId()) {
            $qry = 'INSERT INTO ' . PREFIXE_DB . 'actualite
				(titre, contenu, date_debut, date_modif, date_fin, etat)
				VALUES(:titre, :contenu, :dateDebut, :dateModif, :dateFin, :etat)';
            $params = array(
                'titre' => $actualite->getTitre(),
                'contenu' => $actualite->getContenu(),
                'dateDebut' => $actualite->getDateDebutSql(),
                'dateModif' => $actualite->getDateModifSql(),
                'dateFin' => $actualite->getDateFinSql(),
                'etat' => $actualite->getEtat()
            );
        }
        // -- Mise à jour
        else {
            $qry = 'UPDATE ' . PREFIXE_DB . 'actualite
					SET titre=:titre, contenu=:contenu, date_debut=:dateDebut, date_modif=:dateModif, date_fin=:dateFin, etat=:etat
					WHERE id=:id';
            $params = array(
                'id' => $actualite->getId(),
                'titre' => $actualite->getTitre(),
                'contenu' => $actualite->getContenu(),
                'dateDebut' => $actualite->getDateDebutSql(),
                'dateModif' => $actualite->getDateModifSql(),
                'dateFin' => $actualite->getDateFinSql(),
                'etat' => $actualite->getEtat()
            );
        }
        $res = $this->query($qry, $params);
        if (NULL == $res || count($res) <= 0) {
            throw new DAOException('Erreur lors de la sauvegarde d\'une actualité');
        }
        if (0 == $actualite->getId()) {
            $actualite->setId($this->dbAccessor->getDb()
                ->lastInsertId());
        }
        return $actualite;
    }

    /**
     * supprimer un element
     *
     * @access public
     * @param integer $id            
     * @return boolean
     */
    public function deleteActualite($id)
    {
        $qry = 'DELETE FROM ' . PREFIXE_DB . 'actualite
				WHERE id=?';
        $params = array(
            $id
        );
        $this->query($qry, $params);
        return true;
    }

    /**
     * Supprime des éléments
     *
     * @access public
     * @param array $ids            
     * @return boolean
     */
    public function deleteActualites($ids)
    {}
}
