<?php


namespace App\Dto;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * Class IndexDto
 * @package App\Dto
 *
 * Generic DTO used for listing entity in _embedded
 */
class IndexDto extends AbstractDto
{
    use LinksTrait;
    use EmbeddedTrait;

    /** @var array */
    private $page;


    /**
     * @return mixed
     * @Groups({"index"})
     */
    public function getPage(): ?array
    {
        return $this->page;
    }

    public function setPage(array $page){
        $this->page = $page;
    }


    /**
     * @SerializedName("_links")
     * @Groups({"index"})
     */
    public function getLinks()
    {
        return $this->_links;
    }

    /**
     * @Groups({"index"})
     * @SerializedName("_embedded")
     */
    public function getEmbedded()
    {
        return $this->_embedded;
    }
}