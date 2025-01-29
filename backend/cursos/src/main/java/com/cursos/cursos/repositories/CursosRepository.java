package com.cursos.cursos.repositories;

import com.cursos.cursos.models.entities.Cursos;
import org.springframework.data.repository.CrudRepository;

public interface CursosRepository extends CrudRepository<Cursos, Long> {
}
