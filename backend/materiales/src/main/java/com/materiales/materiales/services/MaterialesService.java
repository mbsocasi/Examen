package com.materiales.materiales.services;

import com.materiales.materiales.models.entities.Materiales;

import java.util.List;
import java.util.Optional;

public interface MaterialesService {
    List<Materiales> findAll();
    Optional<Materiales> findById(Long id);
    Materiales save(Materiales material);
    void deleteById(Long id);
    Materiales update(Long id, Materiales material); // Nuevo método
}
