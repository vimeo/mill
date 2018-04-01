<?php
namespace Mill\Examples\Showtimes\Controllers;

/**
 * @api-label Movies
 */
class Movies
{
    /**
     * Returns all movies for a specific location.
     *
     * @api-label Get movies.
     * @api-group Movies
     *
     * @api-uri:public /movies
     *
     * @api-param:public location (string, required) - Location you want movies for.
     *
     * @api-return:public {collection} \Mill\Examples\Showtimes\Representations\Movie
     *
     * @api-error:public 400 (\Mill\Examples\Showtimes\Representations\Error) - If the location is invalid.
     *
     * @api-version >=1.1.2
     * @api-contentType application/mill.example.movie
     *
     * @api-version <1.1.2
     * @api-contentType application/json
     *
     * @api-version >=1.1
     * @api-param:public page (integer, optional) - Page of results to pull.
     */
    public function GET()
    {
        //
    }

    /**
     * Create a new movie.
     *
     * @api-label Create a movie.
     * @api-group Movies
     *
     * @api-uri:public /movies
     *
     * @api-scope create
     *
     * @api-param:public name (string, required) - Name of the movie.
     * @api-param:public description (string, required) - Description, or tagline, for the movie.
     * @api-param:public runtime (string, optional) - Movie runtime, in `HHhr MMmin` format.
     * @api-param:public content_rating (string, optional) - MPAA rating
     *  + Members
     *      - `G`
     *      - `PG`
     *      - `PG-13`
     *      - `R`
     *      - `NC-17`
     *      - `X`
     *      - `NR`
     *      - `UR`
     * @api-param:public genres (array, optional) - Array of movie genres.
     * @api-param:public director (string, optional) - Name of the director.
     * @api-param:public cast (array, optional) - Array of names of the cast.
     * @api-param:public is_kid_friendly (boolean, optional) - Is this movie kid friendly?
     * @api-param:public rotten_tomatoes_score (integer, optional) - Rotten Tomatoes score
     *
     * @api-return:public {object} \Mill\Examples\Showtimes\Representations\Movie
     *
     * @api-error:public 400 (\Mill\Examples\Showtimes\Representations\Error) - If there is a problem with the request.
     * @api-error:public 400 (\Mill\Examples\Showtimes\Representations\Error) - If the IMDB URL could not be validated.
     *
     * @api-version >=1.1.2
     * @api-contentType application/mill.example.movie
     *
     * @api-version <1.1.2
     * @api-contentType application/json
     *
     * @api-version >=1.1
     * @api-param:public imdb (string, optional) - IMDB URL
     * @api-param:public trailer (string, optional, nullable) - Trailer URL
     *
     * @api-version >=1.1.3
     * @api-return:public {created}
     */
    public function POST()
    {
        //
    }
}
