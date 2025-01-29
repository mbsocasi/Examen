package com.materiales.materiales.repositories;

import com.materiales.materiales.models.entities.Materiales;
import org.springframework.data.repository.CrudRepository;

public interface MaterialesRepository extends CrudRepository<Materiales, Long> {
}