<?php
namespace Model;

class Actualite
{

    /**
     *
     * @var integer
     */
    private $id;

    /**
     *
     * @var string
     */
    private $titre;

    /**
     * Full content without Lire la suite
     * @var string
     */
    private $contenu;
    
    /**
     * With Lire la suite
     * @var string
     */
    private $contenuRaw;

    /**
     * Excerpt without Lire la suite
     * @var string
     */
    private $extrait;

    /**
     *
     * @var int
     */
    private $dateDebut;

    /**
     *
     * @var int
     */
    private $dateModif;

    /**
     *
     * @var int
     */
    private $dateFin;

    /**
     *
     * @var boolean
     */
    private $etat;

    public function __construct($params = array())
    {
        $this->id = isset($params['id']) ? intval($params['id']) : 0;
        $this->titre = @$params['title'];
        $this->contenu = @$params['content'];
        $this->contentRaw = @$params['content_raw'];
        $this->extrait = @$params['excerpt'];
        $this->dateDebut = isset($params['date_start']) ? $params['date_start'] : time();
        $this->dateModif = isset($params['date_update']) ? $params['date_update'] : time();
        $this->dateFin = isset($params['date_end']) ? $params['date_end'] : time() + 3600 * 30;
        $this->etat = isset($params['state']) ? intval($params['state']) : 0;
    }

    public function getExtrait()
    {
        return $this->extrait;
    }

    /**
     *
     * @return \DateTime
     */
    public function getDateTimeStart()
    {
        if (! ($this->dateDebut instanceof \DateTime)) {
            if (empty($this->dateDebut)) {
                return new \DateTime();
            }
            return new \DateTime('@' . $this->dateDebut);
        }
        return $this->dateDebut;
    }

    /**
     *
     * @return \DateTime
     */
    public function getDateTimeUpdate()
    {
        if (! ($this->dateModif instanceof \DateTime)) {
            if (empty($this->dateModif)) {
                return new \DateTime();
            }
            return new \DateTime('@' . $this->dateModif);
        }
        return $this->dateModif;
    }

    /**
     *
     * @return \DateTime
     */
    public function getDateTimeEnd()
    {
        if (! ($this->dateFin instanceof \DateTime)) {
            if (empty($this->dateFin)) {
                $dateFin = new \DateTime();
                $dateFin->modify('+1 month');
                return $dateFin;
            }
            return new \DateTime('@' . $this->dateFin);
        }
        return $this->dateFin;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getTitre()
    {
        return $this->titre;
    }

    public function setTitre($titre)
    {
        $this->titre = $titre;
        return $this;
    }

    public function getContenu()
    {
        return $this->contenu;
    }

    public function setContenu($contenu)
    {
        $this->contenu = $contenu;
        return $this;
    }

    public function setExtrait($extrait)
    {
        $this->extrait = $extrait;
        return $this;
    }

    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;
        return $this;
    }

    public function getDateModif()
    {
        return $this->dateModif;
    }

    public function setDateModif($dateModif)
    {
        $this->dateModif = $dateModif;
        return $this;
    }

    public function getDateFin()
    {
        return $this->dateFin;
    }

    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;
        return $this;
    }

    public function getEtat()
    {
        return $this->etat;
    }

    public function setEtat($etat)
    {
        $this->etat = $etat;
        return $this;
    }

    public function getContenuRaw()
    {
        return $this->contenuRaw;
    }

    public function setContenuRaw($contenuRaw)
    {
        $this->contenuRaw = $contenuRaw;
        return $this;
    }
 
}
